<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchUserProfileDetails {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function fetchUserProfileDetails($user_id) {
        $sql = "SELECT * FROM `users` WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
}
