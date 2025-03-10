<?php
session_start();
// include_once __DIR__ . '/../config/Database.php';
include_once 'Database.php';

// Assuming you have a global logger like Monolog configured
// You can replace the following line with your actual logger setup if needed
// Example: $logger = new Monolog\Logger('user-activity'); 

class User {
    private $db;
    private $logger;

    public function __construct($logger) {
        $this->db = new Database();
        $this->logger = $logger;  // Injecting the logger instance
    }

    /**
     * Register a New User
     */
    // Registration logic can go here

    /**
     * Login User
     */
    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE email = ?";
        $result = $this->db->executeQuery($query, [$username]);
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['uu_id'] = $user['uuid'];
            $_SESSION['username'] = $user['username'];

            // Log successful login
            $this->logger->info('User logged in successfully', [
                'username' => $username,
                'user_id' => $user['id']
            ]);
            
            return true;
        } else {
            // Log failed login attempt
            $this->logger->warning('Failed login attempt', [
                'username' => $username
            ]);
            
            return false;
        }
    }

    /**
     * Check if User is Logged In
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Logout User
     */
    public function logout() {
        // Log user logout
        if (isset($_SESSION['user_id'])) {
            $this->logger->info('User logged out', [
                'user_id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            ]);
        }
        
        session_unset();
        session_destroy();
    }
}
