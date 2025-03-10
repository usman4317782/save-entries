<?php
require_once 'Database.php';

class Brand
{
    private $conn;
    private $table = "brands";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Check for duplicate brand name
    public function isDuplicate($brand_name, $id = null)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE brand_name = :brand_name";
        if ($id) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':brand_name', $brand_name, PDO::PARAM_STR);
        if ($id) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch()['count'] > 0;
    }

    // Fetch all brands with optional filters
    public function getBrands($search = '', $startDate = '', $endDate = '')
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        if (!empty($search)) {
            $query .= " AND brand_name LIKE :search";
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single brand by ID
    public function getBrandById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new brand
    public function createBrand($brand_name, $description)
    {
        if ($this->isDuplicate($brand_name)) {
            return false;
        }
        $query = "INSERT INTO {$this->table} (brand_name, description) VALUES (:brand_name, :description)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':brand_name' => $brand_name,
            ':description' => $description
        ]);
    }

    // Update an existing brand
    public function updateBrand($id, $brand_name, $description)
    {
        if ($this->isDuplicate($brand_name, $id)) {
            return false;
        }
        $query = "UPDATE {$this->table} SET brand_name = :brand_name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':brand_name' => $brand_name,
            ':description' => $description
        ]);
    }

    // Delete a single brand
    public function deleteBrand($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Bulk delete brands
    public function bulkDelete($ids)
    {
        $query = "DELETE FROM {$this->table} WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
        return $this->conn->exec($query);
    }
}
?>
