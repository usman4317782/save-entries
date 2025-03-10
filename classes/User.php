<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function isDuplicate($username, $email, $id = null) {
        try {
            $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE (username = :username OR email = :email) AND deleted_at IS NULL";
            if ($id) {
                $query .= " AND id != :id";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            if ($id) {
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in isDuplicate: " . $e->getMessage());
            throw new Exception("Error checking for duplicate user");
        }
    }

    public function getTotalRecords() {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE username != 'admin' AND deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return intval($stmt->fetch(PDO::FETCH_ASSOC)['total']);
        } catch (PDOException $e) {
            error_log("Error in getTotalRecords: " . $e->getMessage());
            throw new Exception("Error getting total records");
        }
    }

    public function getFilteredRecords($search = '', $start_date = '', $end_date = '') {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE username != 'admin' AND deleted_at IS NULL";
            $params = [];

            if (!empty($search)) {
                $search = '%' . $search . '%';
                $query .= " AND (
                    LOWER(username) LIKE LOWER(:search) OR 
                    LOWER(email) LIKE LOWER(:search) OR 
                    LOWER(COALESCE(first_name, '')) LIKE LOWER(:search) OR 
                    LOWER(COALESCE(last_name, '')) LIKE LOWER(:search)
                )";
                $params[':search'] = $search;
            }

            if (!empty($start_date)) {
                $query .= " AND DATE(created_at) >= :start_date";
                $params[':start_date'] = $start_date;
            }

            if (!empty($end_date)) {
                $query .= " AND DATE(created_at) <= :end_date";
                $params[':end_date'] = $end_date;
            }

            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return intval($stmt->fetch(PDO::FETCH_ASSOC)['total']);
        } catch (PDOException $e) {
            error_log("Error in getFilteredRecords: " . $e->getMessage());
            throw new Exception("Error getting filtered records");
        }
    }

    public function getUsers($search = '', $start_date = '', $end_date = '', $start = 0, $length = 10) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE username != 'admin' AND deleted_at IS NULL";
            $params = [];

            if (!empty($search)) {
                $search = '%' . $search . '%';
                $query .= " AND (
                    LOWER(username) LIKE LOWER(:search) OR 
                    LOWER(email) LIKE LOWER(:search) OR 
                    LOWER(COALESCE(first_name, '')) LIKE LOWER(:search) OR 
                    LOWER(COALESCE(last_name, '')) LIKE LOWER(:search)
                )";
                $params[':search'] = $search;
            }

            if (!empty($start_date)) {
                $query .= " AND DATE(created_at) >= :start_date";
                $params[':start_date'] = $start_date;
            }

            if (!empty($end_date)) {
                $query .= " AND DATE(created_at) <= :end_date";
                $params[':end_date'] = $end_date;
            }

            $query .= " ORDER BY created_at DESC LIMIT :start, :length";
            $params[':start'] = (int)$start;
            $params[':length'] = (int)$length;

            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                if ($key === ':start' || $key === ':length') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUsers: " . $e->getMessage());
            throw new Exception("Error fetching users");
        }
    }

    public function getUserById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id AND username != 'admin' AND deleted_at IS NULL");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            throw new Exception("Error fetching user");
        }
    }

    public function createUser($username, $email, $password, $first_name = null, $last_name = null) {
        try {
            $this->conn->beginTransaction();

            if ($this->isDuplicate($username, $email)) {
                throw new Exception("Username or email already exists");
            }

            $uuid = bin2hex(random_bytes(16));
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO {$this->table} (uuid, username, email, password_hash, first_name, last_name, is_active, is_verified, created_at) 
                     VALUES (:uuid, :username, :email, :password_hash, :first_name, :last_name, 1, 1, NOW())";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
            
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception("Failed to create user");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error in createUser: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function updateUser($id, $username, $email, $password = null, $first_name = null, $last_name = null) {
        try {
            $this->conn->beginTransaction();

            // Check if user exists
            $existingUser = $this->getUserById($id);
            if (!$existingUser) {
                throw new Exception("User not found");
            }

            if ($this->isDuplicate($username, $email, $id)) {
                throw new Exception("Username or email already exists");
            }

            $query = "UPDATE {$this->table} SET 
                     username = :username, 
                     email = :email, 
                     first_name = :first_name, 
                     last_name = :last_name,
                     updated_at = NOW()";

            if (!empty($password)) {
                $query .= ", password_hash = :password_hash";
            }

            $query .= " WHERE id = :id AND username != 'admin' AND deleted_at IS NULL";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);

            if (!empty($password)) {
                $stmt->bindValue(':password_hash', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            }

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception("Failed to update user");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error in updateUser: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    public function deleteUser($id) {
        try {
            $query = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id AND username != 'admin' AND deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in deleteUser: " . $e->getMessage());
            throw new Exception("Error deleting user");
        }
    }

    public function bulkDelete($ids) {
        try {
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $query = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id IN ($placeholders) AND username != 'admin' AND deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($ids);
        } catch (PDOException $e) {
            error_log("Error in bulkDelete: " . $e->getMessage());
            throw new Exception("Error deleting users");
        }
    }

    public function getRoles($userId) {
        try {
            $query = "SELECT r.* FROM roles r 
                     INNER JOIN user_roles ur ON r.id = ur.role_id 
                     WHERE ur.user_id = :user_id AND r.deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRoles: " . $e->getMessage());
            throw new Exception("Error fetching user roles");
        }
    }

    public function assignRole($userId, $roleId) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in assignRole: " . $e->getMessage());
            throw new Exception("Error assigning role to user");
        }
    }

    public function removeRole($userId, $roleId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user_roles WHERE user_id = :user_id AND role_id = :role_id");
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':role_id', $roleId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in removeRole: " . $e->getMessage());
            throw new Exception("Error removing role from user");
        }
    }

    public function hasPermission($userId, $permissionName) {
        try {
            $query = "SELECT COUNT(*) as count FROM permissions p 
                     INNER JOIN role_permissions rp ON p.id = rp.permission_id 
                     INNER JOIN user_roles ur ON rp.role_id = ur.role_id 
                     WHERE ur.user_id = :user_id 
                     AND p.name = :permission_name 
                     AND p.deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':permission_name', $permissionName, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in hasPermission: " . $e->getMessage());
            throw new Exception("Error checking user permission");
        }
    }

    public function getPermissions($userId) {
        try {
            $query = "SELECT DISTINCT p.* FROM permissions p 
                     INNER JOIN role_permissions rp ON p.id = rp.permission_id 
                     INNER JOIN user_roles ur ON rp.role_id = ur.role_id 
                     WHERE ur.user_id = :user_id AND p.deleted_at IS NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPermissions: " . $e->getMessage());
            throw new Exception("Error fetching user permissions");
        }
    }
}
?>