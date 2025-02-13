<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchAllUsers {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function fetchAllUsers() {
        // Fetch users along with their profiles
        $sql = "SELECT users.*, profiles.profile_picture, profiles.bio, profiles.affiliations, profiles.research_interests 
                FROM users 
                LEFT JOIN profiles ON users.id = profiles.user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
}
