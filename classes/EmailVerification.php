<?php
require_once 'config.php';
require_once 'classes/Database.php';

class EmailVerification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function verifyEmail($token) {
        // Check if the token exists and is not expired
        $sql = "SELECT * FROM users WHERE verification_token = ? AND verification_expires_at > ?";
        $currentDateTime = date('Y-m-d H:i:s');
        $result = $this->db->query($sql, [$token, $currentDateTime]);

        if ($result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            
            // Update user as verified
            $updateSql = "UPDATE users SET is_verified = 1, verification_token = NULL, verification_expires_at = NULL, email_verified_at = ? WHERE id = ?";
            $this->db->query($updateSql, [$currentDateTime, $user['id']]);

            return true;
        }

        return false;
    }
}