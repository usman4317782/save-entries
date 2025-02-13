<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchResearchRecord {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // public function fetchResearchRecordByUserId($user_id) {
    //     $sql = "SELECT * FROM `resources` WHERE user_id = :user_id"; // Updated to fetch from resources table
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC); // Changed to fetchAll to return multiple records
    // }
    public function fetchAllResearchRecordForAdmin() {
        $sql = "SELECT * FROM `resources`"; // Updated to fetch from resources table
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Changed to fetchAll to return multiple records
    }

   
}
