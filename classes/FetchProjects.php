<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class ManageProjects {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Method to fetch all projects
    public function getAllProjects() {
        $query = "SELECT p.id AS project_id, p.name, p.description, p.created_at, u.username AS created_by FROM projects p JOIN users u ON p.created_by = u.id ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch only own created projects
    public function getOwnProjects($userId) {
        $query = "SELECT p.id AS project_id, p.name, p.description, p.created_at, u.username AS created_by FROM projects p JOIN users u ON p.created_by = u.id WHERE p.created_by = :userId ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
