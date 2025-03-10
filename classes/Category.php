<?php
require_once 'Database.php';

class Category
{
    private $conn;
    private $table = "categories";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Check for duplicate category name
    public function isDuplicate($name, $id = null)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE category_name = :name";
        if ($id) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        if ($id) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch()['count'] > 0;
    }

    // Fetch all categories with pagination
    public function getCategories($search = '', $type = '', $startDate = '', $endDate = '')
    {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        if (!empty($search)) {
            $query .= " AND category_name LIKE :search";
        }
        if (!empty($type)) {
            $query .= " AND type = :type";
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
        if (!empty($type)) {
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
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

    // Fetch a single category by ID
    public function getCategoryById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create category
    public function createCategory($name, $description, $type)
    {
        if ($this->isDuplicate($name)) {
            return false;
        }
        $query = "INSERT INTO {$this->table} (category_name, description, type) VALUES (:name, :description, :type)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':type' => $type
        ]);
    }

    // Update category
    public function updateCategory($id, $name, $description, $type)
    {
        if ($this->isDuplicate($name, $id)) {
            return false;
        }
        $query = "UPDATE {$this->table} SET category_name = :name, description = :description, type = :type WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':type' => $type
        ]);
    }

    // Delete single category
    public function deleteCategory($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Bulk delete categories
    public function bulkDelete($ids)
    {
        $query = "DELETE FROM {$this->table} WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
        return $this->conn->exec($query);
    }
}
?>