<?php
require_once "Database.php";
require_once "User.php";
session_start();

class Auth
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User();
    }

    // Register User
    public function register($username, $email, $password)
    {
        try {
            $this->db->beginTransaction();
            
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['username' => $username, 'email' => $email, 'password_hash' => $hashed_password]);
            
            // Get the new user's ID
            $userId = $this->db->lastInsertId();
            
            // Assign default 'user' role
            $query = "SELECT id FROM roles WHERE name = 'user' AND deleted_at IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($role) {
                $this->user->assignRole($userId, $role['id']);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in register: " . $e->getMessage());
            throw new Exception("Error registering user");
        }
    }

    // Login User
    public function login($username, $password)
    {
        try {
            $query = "SELECT * FROM users WHERE (username = :username OR email = :email) AND deleted_at IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['username' => $username, 'email' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return "User not found!";
            }

            if (!password_verify($password, $user['password_hash'])) {
                return "Incorrect password!";
            }

            // Get user roles and permissions
            $roles = $this->user->getRoles($user['id']);
            $permissions = $this->user->getPermissions($user['id']);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['roles'] = array_column($roles, 'name');
            $_SESSION['permissions'] = array_column($permissions, 'name');
            $_SESSION['logged_in'] = true;

            return true;
        } catch (Exception $e) {
            error_log("Error in login: " . $e->getMessage());
            throw new Exception("Error logging in");
        }
    }

    // Check if User is Logged In
    public function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // Logout User
    public function logout()
    {
        session_unset();
        session_destroy();
        return true;
    }

    // Check if user has a specific role
    public function hasRole($roleName)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        return in_array($roleName, $_SESSION['roles']);
    }

    // Check if user has a specific permission
    public function hasPermission($permissionName)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        return in_array($permissionName, $_SESSION['permissions']);
    }

    // Check if user has any of the specified roles
    public function hasAnyRole($roles)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        return !empty(array_intersect($roles, $_SESSION['roles']));
    }

    // Check if user has any of the specified permissions
    public function hasAnyPermission($permissions)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        return !empty(array_intersect($permissions, $_SESSION['permissions']));
    }

    // Get current user's roles
    public function getUserRoles()
    {
        return $this->isLoggedIn() ? $_SESSION['roles'] : [];
    }

    // Get current user's permissions
    public function getUserPermissions()
    {
        return $this->isLoggedIn() ? $_SESSION['permissions'] : [];
    }
}
