<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class UpdateUserProfileDetails {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateUserProfileDetails($user_id) {
        // Validate input
        $errors = $this->validateInput($_POST['username'], $_POST['email'], $_POST['password']);

        // Check if email is already registered to another user
        if ($this->isEmailTaken($_POST['email'], $user_id)) {
            $errors['email'] = "Email is already registered to another account.";
        }

        if (!empty($errors)) {
            // If there are errors, return them
            return ['success' => false, 'errors' => $errors];
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash the password if it's provided
        $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

        $sql = "UPDATE `users` SET username = :username, email = :email" . ($hashed_password ? ", password_hash = :password" : "") . " WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if ($hashed_password) {
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        }
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        // Execute the statement
        $result = $stmt->execute();

        // Always return an array
        return ['success' => $result, 'errors' => []];
    }

    private function validateInput($username, $email, $password) {
        $errors = [];

        // Validate username
        if (empty($username)) {
            $errors['username'] = "Username is required.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $errors['username'] = "Username must be 3-20 characters long and can only contain letters, numbers, and underscores.";
        }

        // Validate email
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        // Validate password (optional)
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors['password'] = "Password must be at least 8 characters long.";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                $errors['password'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
            }
        }

        return $errors;
    }

    private function isEmailTaken($email, $user_id) {
        $sql = "SELECT id FROM users WHERE email = :email AND id != :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Returns true if email is found for another user
    }
}
