<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class UserActivity {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function fetchUserActivities() {
        // Fetch user activities along with their personal details
        $sql = "SELECT ua.*, u.username AS name, u.email, ua.login_time, ua.logout_time, ua.ip_address, ua.user_agent 
                FROM user_activity ua
                JOIN users u ON ua.user_id = u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
}
