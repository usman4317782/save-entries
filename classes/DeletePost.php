<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class DeletePost
{
    private $db;
    public $msg;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function deletePost($post_id, $user_id)
    {
        // Delete post
        $sql = "DELETE FROM posts WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $this->msg = "<div class='alert alert-danger'>Failed to delete post.</div>";
            return false;
        }

        $this->msg = "Post deleted successfully.";
        return true;
    }
}
