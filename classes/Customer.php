<?php
require_once 'Database.php';

class Customer
{
    private $conn;
    private $table = "customers";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Check for duplicate contact number
    public function isDuplicate($contact_number, $id = null)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE contact_number = :contact_number";
        if ($id) {
            $query .= " AND customer_id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':contact_number', $contact_number, PDO::PARAM_STR);
        if ($id) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch()['count'] > 0;
    }

    // Fetch all customers with optional filters
    public function getCustomers($search = '', $startDate = '', $endDate = '')
    {
        $query = "SELECT c.*, 
                 (SELECT COALESCE(SUM(s.final_amount), 0) FROM sales s WHERE s.customer_id = c.customer_id) as total_sales,
                 (SELECT COALESCE(SUM(p.amount), 0) FROM payments p JOIN sales s ON p.sale_id = s.id WHERE s.customer_id = c.customer_id) as total_payments,
                 (SELECT COALESCE(SUM(s.final_amount), 0) - COALESCE(SUM(p.amount), 0) 
                  FROM sales s 
                  LEFT JOIN payments p ON s.id = p.sale_id 
                  WHERE s.customer_id = c.customer_id) as closing_balance
                 FROM {$this->table} c WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND c.name LIKE :search";
        }
        if (!empty($startDate)) {
            $query .= " AND c.created_at >= :startDate";
        }
        if (!empty($endDate)) {
            $query .= " AND c.created_at <= :endDate";
        }
        $query .= " ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        if (!empty($startDate)) {
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        }
        if (!empty($endDate)) {
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Fetch a single customer by ID
    public function getCustomerById($id)
    {
        $query = "SELECT c.*, 
                 (SELECT COALESCE(SUM(s.final_amount), 0) FROM sales s WHERE s.customer_id = c.customer_id) as total_sales,
                 (SELECT COALESCE(SUM(p.amount), 0) FROM payments p JOIN sales s ON p.sale_id = s.id WHERE s.customer_id = c.customer_id) as total_payments,
                 (SELECT COALESCE(SUM(s.final_amount), 0) - COALESCE(SUM(p.amount), 0) 
                  FROM sales s 
                  LEFT JOIN payments p ON s.id = p.sale_id 
                  WHERE s.customer_id = c.customer_id) as closing_balance
                 FROM {$this->table} c WHERE c.customer_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create a new customer
    public function createCustomer($name, $address, $contact_number)
    {
        if ($this->isDuplicate($contact_number)) {
            return false;
        }
        $query = "INSERT INTO {$this->table} (name, address, contact_number) VALUES (:name, :address, :contact_number)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':contact_number' => $contact_number
        ]);
    }

    // Update an existing customer
    public function updateCustomer($id, $name, $address, $contact_number)
    {
        if ($this->isDuplicate($contact_number, $id)) {
            return false;
        }
        $query = "UPDATE {$this->table} SET name = :name, address = :address, contact_number = :contact_number WHERE customer_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':address' => $address,
            ':contact_number' => $contact_number
        ]);
    }

    // Delete a single customer
    public function deleteCustomer($id)
    {
        $query = "DELETE FROM {$this->table} WHERE customer_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Bulk delete customers
    public function bulkDelete($ids)
    {
        $query = "DELETE FROM {$this->table} WHERE customer_id IN (" . implode(',', array_map('intval', $ids)) . ")";
        return $this->conn->exec($query);
    }

    // Update customer closing balance
    public function updateClosingBalance($customerId)
    {
        try {
            // Calculate total sales amount
            $salesQuery = "SELECT COALESCE(SUM(final_amount), 0) as total_sales FROM sales WHERE customer_id = :customer_id";
            $salesStmt = $this->conn->prepare($salesQuery);
            $salesStmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
            $salesStmt->execute();
            $totalSales = $salesStmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

            // Calculate total payments
            $paymentsQuery = "SELECT COALESCE(SUM(p.amount), 0) as total_payments 
                             FROM payments p 
                             JOIN sales s ON p.sale_id = s.id 
                             WHERE s.customer_id = :customer_id";
            $paymentsStmt = $this->conn->prepare($paymentsQuery);
            $paymentsStmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
            $paymentsStmt->execute();
            $totalPayments = $paymentsStmt->fetch(PDO::FETCH_ASSOC)['total_payments'];

            // Calculate closing balance
            $closingBalance = $totalSales - $totalPayments;

            // Update customer record
            $updateQuery = "UPDATE {$this->table} SET closing_balance = :closing_balance WHERE customer_id = :customer_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindValue(':closing_balance', $closingBalance, PDO::PARAM_STR);
            $updateStmt->bindValue(':customer_id', $customerId, PDO::PARAM_INT);
            return $updateStmt->execute();
        } catch (PDOException $e) {
            // Log error
            error_log("Error updating customer closing balance: " . $e->getMessage());
            return false;
        }
    }

    // Update all customers' closing balances
    public function updateAllClosingBalances()
    {
        try {
            $query = "SELECT customer_id FROM {$this->table}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($customers as $customer) {
                $this->updateClosingBalance($customer['customer_id']);
            }
            return true;
        } catch (PDOException $e) {
            // Log error
            error_log("Error updating all customer closing balances: " . $e->getMessage());
            return false;
        }
    }
}
?>