<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class UpdateProfile
{
    private $db;
    public $bio, $affiliations, $research_interests, $msg;
    public function __construct()
    {
        // Get the singleton instance of the Database
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateProfile($data, $session_id)
    {
        // Check if the form was submitted via POST
        if (isset($data['update_profile']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

            // Filter and sanitize input data
            $bio = htmlspecialchars(trim($data['bio']), ENT_QUOTES, 'UTF-8');
            $affiliations = htmlspecialchars(trim($data['affiliations']), ENT_QUOTES, 'UTF-8');
            $research_interests = htmlspecialchars(trim($data['research_interests']), ENT_QUOTES, 'UTF-8');

            // Check if profile exists for the current user
            $sql = "SELECT id FROM profiles WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $session_id, PDO::PARAM_INT);
            $stmt->execute();
            $existingProfile = $stmt->fetch(PDO::FETCH_ASSOC);

            // If the profile exists, update it
            if ($existingProfile) {
                $sql = "UPDATE profiles 
                        SET bio = :bio, affiliations = :affiliations, research_interests = :research_interests, updated_at = NOW()
                        WHERE user_id = :user_id";
                $stmt = $this->db->prepare($sql);
            }
            // If no profile exists, insert a new one
            else {
                $sql = "INSERT INTO profiles (user_id, bio, affiliations, research_interests, created_at, updated_at) 
                        VALUES (:user_id, :bio, :affiliations, :research_interests, NOW(), NOW())";
                $stmt = $this->db->prepare($sql);
            }

            // Bind the parameters for both INSERT and UPDATE
            $stmt->bindParam(':user_id', $session_id, PDO::PARAM_INT);
            $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
            $stmt->bindParam(':affiliations', $affiliations, PDO::PARAM_STR);
            $stmt->bindParam(':research_interests', $research_interests, PDO::PARAM_STR);

            // Execute the query
            if ($stmt->execute()) {
?>
                <script>
                    alert('Profile updated successfully.');
                </script>
            <?php
                // return "<p class='text text-center text-uppercase text-success font-weight-bold'>Profile updated successfully.</p>";
            } else {
            ?>
                <script>
                    alert('Error! while updating profile');
                </script>
<?php
                // return "<p class='text text-center text-uppercase text-danger font-weight-bold'>Error updating profile.</p>";
            }
        }
    }

    public function updateProfilePhoto($files, $session_id)
    {
        // Allowed image file extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        // Maximum file size (5 MB in bytes)
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        // Validate that files were uploaded
        if (!isset($files['profile_images']) || empty($files['profile_images']['name'][0])) {
            $this->msg = "<div class='alert alert-danger'>No files uploaded. Please select images to upload.</div>";
            return; // Exit early if no files are uploaded
        }

        // Loop through each uploaded file
        foreach ($files['profile_images']['name'] as $key => $fileName) {
            // Validate file size
            if ($files['profile_images']['size'][$key] > $maxFileSize) {
                $this->msg = "<div class='alert alert-danger'>File size exceeds 5MB limit.</div>";
                return; // Exit early if file size exceeds limit
            }

            // Get file extension
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file extension
            if (!in_array($fileExtension, $allowedExtensions)) {
                $this->msg = "<div class='alert alert-danger'>Invalid file type. Only JPG, JPEG, PNG, GIF, BMP, and WEBP are allowed.</div>";
                return; // Exit early if file type is invalid
            }

            // Validate for errors
            if ($files['profile_images']['error'][$key] !== UPLOAD_ERR_OK) {
                $this->msg = "<div class='alert alert-danger'>File upload error occurred. Please try again.</div>";
                return; // Exit early if there is an upload error
            }

            // Generate a new unique file name to prevent overwriting
            $newFileName = uniqid() . '.' . $fileExtension;

            // Define the target directory and file path
            $uploadDir = '../uploads/profile_images/';
            $uploadFile = $uploadDir . $newFileName;

            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($files['profile_images']['tmp_name'][$key], $uploadFile)) {
                // File uploaded successfully
                $this->msg = "<div class='alert alert-success'>File uploaded: $uploadFile <br></div>";

                // Save to database
                $this->saveProfileImage($session_id, $newFileName);
            } else {
                $this->msg = "<div class='alert alert-danger'>Failed to upload file.</div>";
            }
        }
    }

    // Function to save or update profile image in the database
    private function saveProfileImage($user_id, $fileName)
    {
        // Check if a profile image already exists for the user
        $sql = "SELECT profile_picture FROM profiles WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $existingProfile = $stmt->fetch(PDO::FETCH_ASSOC);

        // If a profile exists, delete the old profile picture
        if ($existingProfile) {
            $oldFilePath = '../uploads/profile_images/' . $existingProfile['profile_picture'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
            }
            $sql = "UPDATE profiles 
                    SET profile_picture = :profile_picture, updated_at = NOW()
                    WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
        }
        // If no profile exists, insert a new record
        else {
            $sql = "INSERT INTO profiles (user_id, profile_picture, created_at, updated_at) 
                    VALUES (:user_id, :profile_picture, NOW(), NOW())";
            $stmt = $this->db->prepare($sql);
        }

        // Bind the parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':profile_picture', $fileName, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            $this->msg = "<div class='alert alert-success'>Profile photo updated successfully!</div>";
        } else {
            $this->msg = "<div class='alert alert-danger'>Error saving profile photo to the database.</div>";
        }
    }

    // public function updateAdminProfile($data, $session_id){
    //     if(isset($data['update_admin_profile']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    //         $username = htmlspecialchars(trim($data['username']), ENT_QUOTES, 'UTF-8');
    //         $email = htmlspecialchars(trim($data['email']), ENT_QUOTES, 'UTF-8');
    //         $password = htmlspecialchars(trim($data['password']), ENT_QUOTES, 'UTF-8');
    //         $role = htmlspecialchars(trim($data['role']), ENT_QUOTES, 'UTF-8');

    //         if (empty($username) || empty($email) || empty($role)) {
    //             $this->msg = "<div class='alert alert-danger'>All fields are required.</div>";
    //             return;
    //         }
    //         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //             $this->msg = "<div class='alert alert-danger'>Invalid email format.</div>";
    //             return;
    //         }
    //         if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    //             $this->msg = "<div class='alert alert-danger'>Invalid username format.</div>";
    //             return;
    //         }
    //         // If the password field is not empty, update the password
    //         if (!empty($password)) {
    //             $password = password_hash($password, PASSWORD_DEFAULT);
    //         }

    //         // Check if the email is already registered with another admin ID
    //         $sql = "SELECT COUNT(*) FROM admin WHERE email = :email AND admin_id != :admin_id";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    //         $stmt->bindParam(':admin_id', $session_id, PDO::PARAM_INT);
    //         $stmt->execute();
    //         $emailCount = $stmt->fetchColumn();

    //         if ($emailCount > 0) {
    //             $this->msg = "<div class='alert alert-danger'>Email is already registered with another admin.</div>";
    //             return; // Exit early if the email is already taken
    //         }

    //         $sql = "UPDATE admin SET 
    //         username = ?, 
    //         email = ?, 
    //         password = ?, 
    //         role = ?, 
    //         updated_at = CURRENT_TIMESTAMP 
    //         WHERE admin_id = ?";

    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([$username, $email, $password, $role, $session_id]);

    //         // Check if the update was successful
    //         if ($stmt->rowCount() > 0) {
    //             $this->msg = "<div class='alert alert-success'>Admin profile updated successfully!</div>";
    //         } else {
    //             $this->msg = "<div class='alert alert-danger'>Error updating admin profile. No changes made.</div>";
    //         }
    //     }
    // }

   
    public function updateAdminProfile($data, $session_id) {
        if (isset($data['update_admin_profile']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars(trim($data['username']), ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars(trim($data['email']), ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars(trim($data['password']), ENT_QUOTES, 'UTF-8');
    
            // Validate required fields
            if (empty($username) || empty($email)) {
                $this->msg = "<div class='alert alert-danger'>Username and Email are required.</div>";
                return;
            }
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->msg = "<div class='alert alert-danger'>Invalid email format.</div>";
                return;
            }
    
            if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                $this->msg = "<div class='alert alert-danger'>Invalid username format.</div>";
                return;
            }
    
            // Check if username or email exists for another user
            $sql = "SELECT COUNT(*) FROM users WHERE (email = :email OR username = :username) AND id != :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $session_id, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            if ($count > 0) {
                $this->msg = "<div class='alert alert-danger'>Username or Email is already taken.</div>";
                return;
            }
    
            // Build the update query dynamically
            $updateFields = "username = :username, email = :email, updated_at = CURRENT_TIMESTAMP";
            $params = [
                ':username' => $username,
                ':email' => $email,
                ':user_id' => $session_id
            ];
    
            // If password is provided, update it
            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $updateFields .= ", password_hash = :password";
                $params[':password'] = $passwordHash;
            }
    
            $sql = "UPDATE users SET $updateFields WHERE id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
    
            // Check if the update was successful
            if ($stmt->rowCount() > 0) {
                $this->msg = "<div class='alert alert-success'>Profile updated successfully!</div>";
            } else {
                $this->msg = "<div class='alert alert-danger'>No changes made to the profile.</div>";
            }
        }
    }
    
}
