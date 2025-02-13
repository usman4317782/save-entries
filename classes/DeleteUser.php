<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class DeleteUser {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function deleteUser($id) {
        // Fetch users along with their profiles
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

   
}
