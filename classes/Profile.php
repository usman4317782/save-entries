<?php

// require_once 'config.php';
require_once 'Database.php';


class Profile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // public function getProfileDetails($user_id) {
    //     $sql = "SELECT * FROM profiles WHERE user_id = :user_id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch();
    // }
    // public function getAdminProfileDetails($user_id) {
    //     // Updated SQL query to select from the admin table
    //     $sql = "SELECT * FROM admin WHERE admin_id = :user_id"; // Changed from profiles to admin
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch();
    // }

    public function getProfileDetails($user_id) {
        $sql = "SELECT username, email FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}

