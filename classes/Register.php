<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class Register {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function registerUser($username, $email, $password, $confirm_password, $role) {
        // Validate input
        $errors = $this->validateInput($username, $email, $password, $confirm_password);

        // Check if username or email already exists
        $existingUser = $this->checkExistingUser($username, $email);
        if ($existingUser) {
            $errors = array_merge($errors, $existingUser);
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert user into database
            $sql = "INSERT INTO users (username, email, password_hash, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $this->db->query($sql, [$username, $email, $hashed_password, $role]);

            return ['success' => true, 'message' => 'Registration successful'];
        } catch (PDOException $e) {
            return ['success' => false, 'errors' => ['An error occurred during registration. Please try again.']];
        }
    }

    private function validateInput($username, $email, $password, $confirm_password) {
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

        // Validate password
        if (empty($password)) {
            $errors['password'] = "Password is required.";
        }

        // Validate confirm password
        if (empty($confirm_password)) {
            $errors['confirm_password'] = "Confirm password is required.";
        } elseif ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        return $errors;
    }

    private function checkExistingUser($username, $email) {
        $errors = [];

        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $result = $this->db->query($sql, [$username]);
        if ($result->rowCount() > 0) {
            $errors['username'] = "Username already exists.";
        }

        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $result = $this->db->query($sql, [$email]);
        if ($result->rowCount() > 0) {
            $errors['email'] = "Email already registered.";
        }

        return $errors;
    }
}
