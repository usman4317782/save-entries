<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class ManageDiscussionBoardForProjects {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Method to create a new discussion board
    public function createDiscussionBoard($projectId, $userId, $title, $content) {
        $query = "INSERT INTO posts (user_id, title, content, project_id, created_at, updated_at) VALUES (:userId, :title, :content, :projectId, NOW(), NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "Discussion Board created successfully!";
        } else {
            return "Failed to create Discussion Board.";
        }
    }

    // Method to fetch all list of project discussion boards created by user
    public function fetchUserDiscussionBoards($userId) {
        $query = "
            SELECT DISTINCT 
                posts.post_id, 
                posts.user_id, 
                posts.title, 
                posts.content, 
                posts.created_at, 
                posts.updated_at, 
                posts.project_id, 
                projects.name AS project_name, 
                project_members.role AS member_role
            FROM posts
            LEFT JOIN projects ON posts.project_id = projects.id
            LEFT JOIN project_members ON posts.project_id = project_members.project_id
            WHERE 
                posts.user_id = :userId
                OR project_members.user_id = :userId
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: []; // Ensure an array is always returned
        } catch (PDOException $e) {
            error_log("Error fetching discussion boards: " . $e->getMessage());
            return [];
        }
    }
    
    // Method to store a reply in the comments table
    public function storeReply($postId, $userId, $content) {
        $query = "INSERT INTO proejct_discussion_board_comments (post_id, user_id, content, created_at, updated_at) VALUES (:postId, :userId, :content, NOW(), NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        try {
            $stmt->execute();
            return "Reply stored successfully!";
        } catch (PDOException $e) {
            echo "Error storing reply: " . $e->getMessage();
            return "Failed to store reply.";
        }
    }

    // Method to delete a discussion board
    public function deleteDiscussionBoard($boardId) {
        $query = "DELETE FROM posts WHERE post_id = :boardId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':boardId', $boardId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "Discussion Board deleted successfully!";
        } else {
            return "Failed to delete Discussion Board.";
        }
    }

    // Method to fetch all comments of a discussion board
    public function fetchDiscussionBoardComments($boardId) {
        $query = "
            SELECT 
                project_discussion_board_comments.comment_id, 
                project_discussion_board_comments.post_id, 
                project_discussion_board_comments.user_id, 
                project_discussion_board_comments.content, 
                project_discussion_board_comments.created_at, 
                project_discussion_board_comments.updated_at, 
                users.name AS user_name
            FROM project_discussion_board_comments
            LEFT JOIN users ON project_discussion_board_comments.user_id = users.id
            WHERE project_discussion_board_comments.post_id = :boardId
            ORDER BY project_discussion_board_comments.created_at DESC
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':boardId', $boardId, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: []; // Ensure an array is always returned
        } catch (PDOException $e) {
            error_log("Error fetching discussion board comments: " . $e->getMessage());
            return [];
        }
    }
}