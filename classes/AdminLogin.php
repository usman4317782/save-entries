<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class AdminLogin
{
    private $db;

    public function __construct()
    {
        // Get the singleton instance of the Database
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($email, $password)
    {
        // Check if the email is a valid email address

        if (empty($email) || empty($password)) {
            return "Email and password are required."; // Email and password are required
        }
        
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format."; // Email format is invalid
        }


        // SQL query to find the admin with the provided email
        $sql = "SELECT * FROM admin WHERE email = :email LIMIT 1"; // Updated to use 'admin' table
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch();

        // Check if the email is valid
        if (!$admin) {
            return "Invalid not found."; // Email not found
        }

        // If admin exists but password is incorrect
        if (!isset($admin['password']) || !password_verify($password, $admin['password'])) { // Updated to use 'password'
            return "Incorrect password."; // Password incorrect
        }

        // Check if the admin is verified (assuming a verification field exists)
        // If there's no verification field, this check can be removed
        if ($admin['role'] !== 'admin') { // Assuming role check for verification
            return "User not verified. Kindly verify your profile first and then try again."; // User is not verified
        }

        // Set admin session
        $_SESSION['admin_id'] = $admin['admin_id']; // Updated to use 'admin_id'
        $_SESSION['admin_email'] = $admin['email']; // Updated to use 'email'
        $_SESSION['admin_name'] = $admin['username']; // Updated to use 'username'
        return true; // Login successful
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['admin_id']);
    }
}
