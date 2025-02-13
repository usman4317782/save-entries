<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchTotalUsers {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function fetchTotalUsers() {
        // Validate email
        $sql = "SELECT COUNT(*) AS total_users FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

   
}
