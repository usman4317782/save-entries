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

    // Method to delete a project
    public function deleteProject($projectId) {
        $query = "DELETE FROM projects WHERE id = :project_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Project deleted successfully!";
        } else {
            return "Failed to delete project.";
        }
    }

    // Method to fetch project details
    public function getProjectDetail($projectId) {
        $query = "SELECT p.id AS project_id, p.name, p.description, p.created_at, u.username AS created_by FROM projects p JOIN users u ON p.created_by = u.id WHERE p.id = :project_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to update a project
    public function updateProject($projectId, $name, $description) {
        $query = "UPDATE projects SET name = :name, description = :description WHERE id = :project_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Project updated successfully!";
        } else {
            return "Failed to update project.";
        }
    }

    // Method to add a member to a project
    public function addProjectMember($projectId, $memberId) {
        // Check if the user is already a member
        $query = "SELECT * FROM project_members WHERE project_id = :project_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':user_id', $memberId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "User is already a member of this project!";
        } else {
            // Add the user as a project member
            $query = "INSERT INTO project_members (project_id, user_id, role) VALUES (:project_id, :user_id, 'member')";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':project_id', $projectId);
            $stmt->bindParam(':user_id', $memberId);
            $stmt->execute();
            // Fetch project details to include in the notification message
            $projectDetails = $this->getProjectDetail($projectId);
            // Log the action in the notifications table with project details
            $message = "You have been added to a new project: " . $projectDetails['name'] . " (" . $projectDetails['description'] . ").";
            $this->logNotification($memberId, $message);
            return "Member added successfully!";
        }
    }

    // Method to remove a member from a project
    public function removeProjectMember($projectId, $memberId) {
        $query = "DELETE FROM project_members WHERE project_id = :project_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':project_id', $projectId);
        $stmt->bindParam(':user_id', $memberId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Fetch project details to include in the notification message
            $projectDetails = $this->getProjectDetail($projectId);
            // Log the action in the notifications table with project details
            $message = "You have been removed from a project: " . $projectDetails['name'] . " (" . $projectDetails['description'] . ").";
            $this->logNotification($memberId, $message);
            return "Member removed successfully!";
        } else {
            return "Failed to remove member.";
        }
    }

    // Method to fetch all users
    public function getAllUsers() {
        $query = "SELECT id, username FROM users";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch all projects with their members
    public function getProjectsWithMembers() {
        $query = "
            SELECT 
                p.id AS project_id, 
                p.name AS project_name, 
                p.description AS project_description, 
                u.username AS member_name, 
                pm.role AS member_role 
            FROM 
                projects p
            LEFT JOIN 
                project_members pm ON p.id = pm.project_id
            LEFT JOIN 
                users u ON pm.user_id = u.id
            ORDER BY 
                p.id, u.username";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $projects = [];
        foreach ($result as $row) {
            $projects[$row['project_id']]['name'] = $row['project_name'];
            $projects[$row['project_id']]['description'] = $row['project_description'];
            $projects[$row['project_id']]['members'][] = [
                'name' => $row['member_name'],
                'role' => $row['member_role'],
            ];
        }

        return $projects;
    }

    public function getOwnProjects($userId) {
        $query = "SELECT p.id AS project_id, p.name, p.description, p.created_at, u.username AS created_by FROM projects p JOIN users u ON p.created_by = u.id WHERE p.created_by = :userId ORDER BY p.created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to fetch all users excluding the current user
    public function getOtherUsers($currentUserId) {
        $query = "SELECT id, username FROM users WHERE id != :current_user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':current_user_id', $currentUserId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to log a notification
    private function logNotification($userId, $message) {
        $query = "INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
    }

    // Method to show notifications specific to a user
    public function getUserNotifications($userId) {
        $query = "SELECT message FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}