<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class Login {
    private $db;

    public function __construct() {
        // Get the singleton instance of the Database
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($loginInput, $password) {
        // Determine if input is an email or a username
        $column = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // SQL query to find the user by email or username
        $sql = "SELECT * FROM users WHERE $column = :loginInput LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':loginInput', $loginInput, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if user exists
        if (!$user) {
            return "Invalid credentials. User not found.";
        }

        // Verify password
        if (!isset($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return "Incorrect password.";
        }

        // Check if user is verified
        if ($user['is_verified'] == 0) {
            return "User not verified. Kindly verify your profile first and then try again.";
        }

        // Set user session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['username'];

        // Log user activity
        $this->logUserActivity($user['id']);

        return true; // Login successful
    }

    private function logUserActivity($userId) {
        $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address
        $userAgent = $_SERVER['HTTP_USER_AGENT']; // Get user's browser details

        // SQL query to insert user activity
        $sql = "INSERT INTO user_activity (user_id, login_time, ip_address, user_agent) VALUES (:user_id, NOW(), :ip_address, :user_agent)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':ip_address', $ipAddress, PDO::PARAM_STR);
        $stmt->bindParam(':user_agent', $userAgent, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
