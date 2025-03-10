<?php
require_once 'Database.php';

class Vendor
{
    private $conn;
    private $table = "vendors";

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
            $query .= " AND vendor_id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':contact_number', $contact_number, PDO::PARAM_STR);
        if ($id) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch()['count'] > 0;
    }

    // Fetch all vendors with optional filters
    public function getVendors($search = '', $startDate = '', $endDate = '')
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

    // Fetch a single vendor by ID
    public function getVendorById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE vendor_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create a new vendor
    public function createVendor($name, $address, $contact_number)
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

    // Update an existing vendor
    public function updateVendor($id, $name, $address, $contact_number)
    {
        if ($this->isDuplicate($contact_number, $id)) {
            return false;
        }
        $query = "UPDATE {$this->table} SET name = :name, address = :address, contact_number = :contact_number WHERE vendor_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':address' => $address,
            ':contact_number' => $contact_number
        ]);
    }

    // Delete a single vendor
    public function deleteVendor($id)
    {
        $query = "DELETE FROM {$this->table} WHERE vendor_id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Bulk delete vendors
    public function bulkDelete($ids)
    {
        $query = "DELETE FROM {$this->table} WHERE vendor_id IN (" . implode(',', array_map('intval', $ids)) . ")";
        return $this->conn->exec($query);
    }
}
?>