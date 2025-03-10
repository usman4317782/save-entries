<?php
require_once 'Database.php';

class Permission {
    private $conn;
    private $table = "permissions";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE deleted_at IS NULL");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAll permissions: " . $e->getMessage());
            throw new Exception("Error fetching permissions");
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getById permission: " . $e->getMessage());
            throw new Exception("Error fetching permission");
        }
    }

    public function create($name, $description = null) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, description) VALUES (:name, :description)");
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error in create permission: " . $e->getMessage());
            throw new Exception("Error creating permission");
        }
    }

    public function update($id, $name, $description = null) {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET name = :name, description = :description, updated_at = NOW() WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in update permission: " . $e->getMessage());
            throw new Exception("Error updating permission");
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in delete permission: " . $e->getMessage());
            throw new Exception("Error deleting permission");
        }
    }

    public function getRoles($permissionId) {
        try {
            $query = "SELECT r.* FROM roles r 
                     INNER JOIN role_permissions rp ON r.id = rp.role_id 
                     WHERE rp.permission_id = :permission_id AND r.deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':permission_id', $permissionId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRoles: " . $e->getMessage());
            throw new Exception("Error fetching permission roles");
        }
    }
}
