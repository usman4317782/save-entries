<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class ResetForgotPassword
{
    private $errors = [];
    private $db;
    private $success_message = '';
    private $token;
    private $user_id;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->validateToken();
    }

    private function validateToken()
    {
        if (!isset($_GET['token']) || empty($_GET['token'])) {
            $this->errors['token'] = "Invalid or missing token.";
        } else {
            $this->token = $_GET['token'];
            $this->verifyTokenInDatabase();
        }
    }

    private function verifyTokenInDatabase() {
        $sql = "SELECT id FROM users WHERE verification_token = :token AND verification_expires_at > NOW()";
        $params = [':token' => $this->token];
        $result = $this->db->query($sql, $params)->fetch();

        if (!$result) {
            $this->errors['token'] = "Invalid or expired token.";
        } else {
            $this->user_id = $result['id'];
        }
    }

  
    public function handlePasswordReset()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($this->errors)) {
            $this->validatePasswordReset();
        }
    }

    private function validatePasswordReset()
    {
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        $this->validatePassword($password);
        $this->validateConfirmPassword($password, $confirm_password);

        if (empty($this->errors)) {
            $this->updatePassword($password);
        }
    }

    private function validatePassword($password)
    {
        if (empty($password)) {
            $this->errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8) {
            $this->errors['password'] = "Password must be at least 8 characters long.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $this->errors['password'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
        }
    }

    private function validateConfirmPassword($password, $confirm_password)
    {
        if (empty($confirm_password)) {
            $this->errors['confirm_password'] = "Confirm password is required.";
        } elseif ($password !== $confirm_password) {
            $this->errors['confirm_password'] = "Passwords do not match.";
        }
    }

    private function updatePassword($password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = :password, verification_token = NULL, verification_expires_at = NULL WHERE id = :user_id";
        $params = [
            ':password' => $hashed_password,
            ':user_id' => $this->user_id
        ];
        $stmt = $this->db->query($sql, $params);

        if ($stmt->rowCount() > 0) {
            $this->success_message = "Your password has been successfully reset. You can now login with your new password.";
        } else {
            $this->errors['update'] = "Failed to update password. Please try again.";
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessMessage()
    {
        return $this->success_message;
    }
}
