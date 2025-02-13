<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class ActiveInActiveUser {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function activeUser($id) {
        // Fetch users along with their profiles
        $sql = "UPDATE users SET is_verified = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function inactiveUser($id) {
        // Fetch users along with their profiles
        $sql = "UPDATE users SET is_verified = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

   
}
