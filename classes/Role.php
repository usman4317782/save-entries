<?php
require_once 'Database.php';

class Role {
    private $conn;
    private $table = "roles";

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
            error_log("Error in getAll roles: " . $e->getMessage());
            throw new Exception("Error fetching roles");
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getById role: " . $e->getMessage());
            throw new Exception("Error fetching role");
        }
    }

    public function getPermissions($roleId) {
        try {
            $query = "SELECT p.* FROM permissions p 
                     INNER JOIN role_permissions rp ON p.id = rp.permission_id 
                     WHERE rp.role_id = :role_id AND p.deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPermissions: " . $e->getMessage());
            throw new Exception("Error fetching role permissions");
        }
    }

    public function create($name, $description = null) {
        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, description) VALUES (:name, :description)");
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->execute();
            
            $roleId = $this->conn->lastInsertId();
            $this->conn->commit();
            return $roleId;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in create role: " . $e->getMessage());
            throw new Exception("Error creating role");
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
            error_log("Error in update role: " . $e->getMessage());
            throw new Exception("Error updating role");
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in delete role: " . $e->getMessage());
            throw new Exception("Error deleting role");
        }
    }

    public function assignPermission($roleId, $permissionId) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
            $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            $stmt->bindValue(':permission_id', $permissionId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in assignPermission: " . $e->getMessage());
            throw new Exception("Error assigning permission to role");
        }
    }

    public function removePermission($roleId, $permissionId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM role_permissions WHERE role_id = :role_id AND permission_id = :permission_id");
            $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            $stmt->bindValue(':permission_id', $permissionId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in removePermission: " . $e->getMessage());
            throw new Exception("Error removing permission from role");
        }
    }
}
