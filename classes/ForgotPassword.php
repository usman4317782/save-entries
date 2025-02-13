<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class ForgotPassword {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function sendResetLink($email) {
        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email address.'];
        }

        // Check if email exists in the database
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return ['success' => false, 'error' => 'Email address not found.'];
        }

        // Generate reset token and expiration date
        $reset_token = bin2hex(random_bytes(16));
        $reset_expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update user with reset token and expiration
        $this->updateUserResetToken($user['id'], $reset_token, $reset_expires_at);

        // Send password reset email
        $emailSent = $this->sendPasswordResetEmail($email, $reset_token);

        if ($emailSent) {
            return ['success' => true, 'message' => 'Password reset link has been sent to your email.'];
        } else {
            return ['success' => false, 'error' => 'Failed to send password reset email. Please try again later.'];
        }
    }

    private function getUserByEmail($email) {
        $sql = "SELECT id, email FROM users WHERE email = ?";
        $result = $this->db->query($sql, [$email]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    private function updateUserResetToken($userId, $resetToken, $resetExpiresAt) {
        $sql = "UPDATE users SET verification_token = ?, verification_expires_at = ? WHERE id = ?";
        $this->db->query($sql, [$resetToken, $resetExpiresAt, $userId]);
    }

    private function sendPasswordResetEmail($email, $token) {
        // Include PHPMailer
        require_once __DIR__ . '/../PHPMailer/PHPMailerAutoload.php';

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Username = ""; // Replace with your Gmail address
        $mail->Password = ""; // Replace with your Gmail password or app password
        $mail->SetFrom("", "saveentries"); // Replace with your email and name
        $mail->Subject = "Reset Your ResearchHub Password";

        // Construct the reset password link using the PASSWORD_RESET_URL constant
        $resetLink = PASSWORD_RESET_URL . "?token=" . $token;

        $mail->Body = "
            <h2>Reset Your ResearchHub Password</h2>
            <p>You have requested to reset your password. Please click the link below to reset your password:</p>
            <p><a href='{$resetLink}'>{$resetLink}</a></p>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn't request a password reset, please ignore this email.</p>
        ";
        $mail->AddAddress($email);

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            )
        );

        if (!$mail->Send()) {
            // Log the error or handle it appropriately
            error_log("Password reset email sending failed: " . $mail->ErrorInfo);
            return false;
        }
        return true;
    }
}
