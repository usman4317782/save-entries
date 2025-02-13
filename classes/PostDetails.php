<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class PostDetails {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // Get post details by post_id
    public function getPostDetails($postId) {
        $stmt = $this->pdo->prepare("SELECT p.*, u.username AS user_name FROM posts p JOIN users u ON p.user_id = u.id WHERE p.post_id = ?");
        $stmt->execute([$postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get only top-level comments (comments without parent_comment_id)
    public function getTopLevelComments($postId) {
        $stmt = $this->pdo->prepare("SELECT c.*, u.username AS user_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? AND c.parent_comment_id IS NULL ORDER BY c.created_at DESC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a reply to a comment or a post
    public function addReply($postId, $replyContent, $parentCommentId = null) {
        $userId = $_SESSION['user_id']; // Assuming user is logged in and session holds user_id
        $stmt = $this->pdo->prepare("INSERT INTO comments (post_id, user_id, parent_comment_id, content) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$postId, $userId, $parentCommentId, $replyContent])) {
            return true;
        } else {
            return false;
        }
    }

    // Handle voting (upvote or downvote) on comments
    public function voteOnComment($commentId, $voteType, $postId) {
        $userId = $_SESSION['user_id']; // Assuming user is logged in and session holds user_id
        $stmt = $this->pdo->prepare("INSERT INTO votes (user_id, comment_id, vote_type, post_id) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE vote_type = ?");
        if ($stmt->execute([$userId, $commentId, $voteType, $postId, $voteType])) {
            return true;
        } else {
            return false;
        }
    }
}
