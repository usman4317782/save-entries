<?php

// require_once 'config.php';
require_once 'Database.php';


class Profile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getProfileDetails($user_id) {
        $sql = "SELECT username, email FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchSingleRecord($user_id) {
        $sql = "SELECT * FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deleteProfile($user_id) {
        $sql = "DELETE FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }


}

