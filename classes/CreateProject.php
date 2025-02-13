<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class CreateProject {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    // New method to create a project
    public function createProject($name, $description) {
        if (empty($name) || empty($description)) {
            return "All fields are required.";
        }

        $userId = $_SESSION['user_id']; // Assume user is logged in

        // Insert project details
        $query = "INSERT INTO projects (name, description, created_by) VALUES (:name, :description, :created_by)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':created_by', $userId);
        $stmt->execute();

        $projectId = $this->pdo->lastInsertId();

        // Add creator to project members
        $query = "INSERT INTO project_members (project_id, user_id, role) VALUES (:project_id, :user_id, 'admin')";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return "Project created successfully!";
    }
}
