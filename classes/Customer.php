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
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        if (!empty($search)) {
            $query .= " AND name LIKE :search";
        }
        if (!empty($startDate)) {
            $query .= " AND created_at >= :startDate";
        }
        if (!empty($endDate)) {
            $query .= " AND created_at <= :endDate";
        }
        $query .= " ORDER BY created_at DESC";
        
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
        $query = "SELECT * FROM {$this->table} WHERE customer_id = :id";
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
}
?>