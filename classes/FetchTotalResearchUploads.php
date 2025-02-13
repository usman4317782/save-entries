<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchTotalResearchUploads {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function fetchTotalResearchUploads($userId) {
        // Validate email
        $sql = "SELECT COUNT(*) AS total_resources FROM resources WHERE user_id = $userId";       
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function fetchAllTotalResearchUploads() {
        // Validate email
        $sql = "SELECT COUNT(*) AS total_resources FROM resources";       
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

   
}
