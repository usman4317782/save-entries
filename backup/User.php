<?php
require_once 'Database.php';

class User
{
    private $conn;
    private $table = "users";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Generate unique UUID (minimum 60 characters using password_hash)
    private function generateUUID()
    {
        $randomString = bin2hex(random_bytes(30)); // 60 characters hex
        return password_hash($randomString, PASSWORD_BCRYPT); // Results in 60+ characters
    }

    // Check for duplicate username or email
    public function isDuplicate($username, $email, $id = null)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE (username = :username OR email = :email)";
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
        return $stmt->fetch()['count'] > 0;
    }

    // Fetch all users with optional filters
    public function getUsers($search = '', $startDate = '', $endDate = '')
    {
        $query = "SELECT id, username, email, first_name, last_name, created_at 
                 FROM {$this->table} 
                 WHERE deleted_at IS NULL";
        if (!empty($search)) {
            $query .= " AND (username LIKE :search OR email LIKE :search)";
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

    // Fetch a single user by ID
    public function getUserById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create a new user
    public function createUser($username, $email, $password, $first_name, $last_name)
    {
        if ($this->isDuplicate($username, $email)) {
            return false;
        }
        $uuid = $this->generateUUID();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO {$this->table} 
                 (uuid, username, email, password_hash, first_name, last_name) 
                 VALUES (:uuid, :username, :email, :password_hash, :first_name, :last_name)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':uuid' => $uuid,
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $password_hash,
            ':first_name' => $first_name ?: null,
            ':last_name' => $last_name ?: null
        ]);
    }

    // Update an existing user
    public function updateUser($id, $username, $email, $password, $first_name, $last_name)
    {
        if ($this->isDuplicate($username, $email, $id)) {
            return false;
        }
        
        $params = [
            ':id' => $id,
            ':username' => $username,
            ':email' => $email,
            ':first_name' => $first_name ?: null,
            ':last_name' => $last_name ?: null
        ];
        
        $query = "UPDATE {$this->table} SET 
                 username = :username,
                 email = :email,
                 first_name = :first_name,
                 last_name = :last_name";
        
        if (!empty($password)) {
            $query .= ", password_hash = :password_hash";
            $params[':password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    // Bulk delete users (soft delete)
    public function bulkDelete($ids)
    {
        $query = "UPDATE {$this->table} 
                 SET deleted_at = CURRENT_TIMESTAMP 
                 WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
        return $this->conn->exec($query);
    }
}
?>