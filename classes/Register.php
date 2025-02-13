<?php

// require_once __DIR__ . '/../config.php';
// require_once __DIR__ . '/Database.php';

// class Register {
//     private $db;

//     public function __construct() {
//         $this->db = Database::getInstance();
//     }

//     public function registerUser($username, $email, $password, $confirm_password) {
//         // Validate input
//         $errors = $this->validateInput($username, $email, $password, $confirm_password);

//         // Check if username or email already exists
//         $existingUser = $this->checkExistingUser($username, $email);
//         if ($existingUser) {
//             $errors = array_merge($errors, $existingUser);
//         }

//         if (!empty($errors)) {
//             // If there are errors, return them
//             return ['success' => false, 'errors' => $errors];
//         }

//         // Hash the password
//         $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//         // Generate verification token and expiration date
//         $verification_token = bin2hex(random_bytes(16));
//         $verification_expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));

//         try {
//             // Insert user into database
//             $sql = "INSERT INTO users (username, email, password_hash, verification_token, verification_expires_at) VALUES (?, ?, ?, ?, ?)";
//             $this->db->query($sql, [$username, $email, $hashed_password, $verification_token, $verification_expires_at]);

//             // Send verification email (implement this method separately)
//             $this->sendVerificationEmail($email, $verification_token);

//             return ['success' => true, 'message' => 'Registration successful. Please check your email to verify your account.'];
//         } catch (PDOException $e) {
//             return ['success' => false, 'errors' => ['An error occurred during registration. Please try again.']];
//         }
//     }

//     private function validateInput($username, $email, $password, $confirm_password) {
//         $errors = [];

//         // Validate username
//         if (empty($username)) {
//             $errors['username'] = "Username is required.";
//         } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
//             $errors['username'] = "Username must be 3-20 characters long and can only contain letters, numbers, and underscores.";
//         }

//         // Validate email
//         if (empty($email)) {
//             $errors['email'] = "Email is required.";
//         } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//             $errors['email'] = "Invalid email format.";
//         }

//         // Validate password
//         if (empty($password)) {
//             $errors['password'] = "Password is required.";
//         } elseif (strlen($password) < 8) {
//             $errors['password'] = "Password must be at least 8 characters long.";
//         } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
//             $errors['password'] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
//         }

//         // Validate confirm password
//         if (empty($confirm_password)) {
//             $errors['confirm_password'] = "Confirm password is required.";
//         } elseif ($password !== $confirm_password) {
//             $errors['confirm_password'] = "Passwords do not match.";
//         }

//         return $errors;
//     }

//     private function checkExistingUser($username, $email) {
//         $errors = [];

//         // Check if username already exists
//         $sql = "SELECT id FROM users WHERE username = ?";
//         $result = $this->db->query($sql, [$username]);
//         if ($result->rowCount() > 0) {
//             $errors['username'] = "Username already exists.";
//         }

//         // Check if email already exists
//         $sql = "SELECT id FROM users WHERE email = ?";
//         $result = $this->db->query($sql, [$email]);
//         if ($result->rowCount() > 0) {
//             $errors['email'] = "Email already registered.";
//         }

//         return $errors;
//     }

//     private function sendVerificationEmail($email, $token) {
//         // Include PHPMailer
//         require_once __DIR__ . '/../PHPMailer/PHPMailerAutoload.php';

//         $mail = new PHPMailer();
//         $mail->IsSMTP();
//         $mail->SMTPAuth = true;
//         $mail->SMTPSecure = 'tls';
//         $mail->Host = "smtp.gmail.com";
//         $mail->Port = 587;
//         $mail->IsHTML(true);
//         $mail->CharSet = 'UTF-8';
//         $mail->Username = "co.testing.phpmail@gmail.com"; // Replace with your Gmail address
//         $mail->Password = "etic grso kuga baxv"; // Replace with your Gmail password or app password
//         $mail->SetFrom("co.testing.phpmail@gmail.com", "ResearchHub"); // Replace with your email and name
//         $mail->Subject = "Verify Your ResearchHub Account";
//         // Construct the verification link using the BASE_URL constant
//         $verificationLink = EMAIL_VERIFICATION_URL . "?token=" . $token; 

//         // $verificationLink = "http://localhost/researchhub/verify.php?token=" . $token; // Replace with your actual verification URL
//         $mail->Body = "
//             <h2>Welcome to ResearchHub!</h2>
//             <p>Thank you for registering. Please click the link below to verify your email address:</p>
//             <p><a href='{$verificationLink}'>{$verificationLink}</a></p>
//             <p>If you didn't register for ResearchHub, please ignore this email.</p>
//         ";
//         $mail->AddAddress($email);

//         $mail->SMTPOptions = array(
//             'ssl' => array(
//                 'verify_peer' => false,
//                 'verify_peer_name' => false,
//                 'allow_self_signed' => false
//             )
//         );

//         if (!$mail->Send()) {
//             // Log the error or handle it appropriately
//             error_log("Email sending failed: " . $mail->ErrorInfo);
//             return false;
//         }
//         return true;
//     }
// }


require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class Register {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function registerUser($username, $email, $password, $confirm_password) {
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
            $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $this->db->query($sql, [$username, $email, $hashed_password]);

            return ['success' => true, 'message' => 'Registration successful. You can now log in.'];
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
