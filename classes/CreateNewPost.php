<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class CreateNewPost
{
    private $db;
    public $msg;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createNewPost($title, $content, $user_id, $tags)
    {
        if (empty($title) || empty($content)) {
            $this->msg = "<div class='alert alert-danger'>Title and content are required.</div>";
            return false;
        }

        // Secure text
        $title = htmlspecialchars($title);
        $content = htmlspecialchars($content);
        $tags = htmlspecialchars($tags);

        // Insert post
        $sql = "INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $user_id);
        if (!$stmt->execute()) {
            $this->msg = "<div class='alert alert-danger'>Failed to create post.</div>";
            return false;
        }

        // Capture the post_id of the newly created post
        $postId = $this->db->lastInsertId();

        // Insert tags
        $tagArray = explode(',', $tags);
        foreach ($tagArray as $tag) {
            $tag = trim($tag);

            // Check if tag exists, if not, insert it
            $sqlTag = "SELECT tag_id FROM tags WHERE name = :tag";
            $stmtTag = $this->db->prepare($sqlTag);
            $stmtTag->bindParam(':tag', $tag);
            $stmtTag->execute();
            $tagId = $stmtTag->fetchColumn();
            if (!$tagId) {
                $sqlInsertTag = "INSERT INTO tags (name) VALUES (:tag)";
                $stmtInsertTag = $this->db->prepare($sqlInsertTag);
                $stmtInsertTag->bindParam(':tag', $tag);
                $stmtInsertTag->execute();
                $tagId = $this->db->lastInsertId();
            }

            // Insert post-tag relationship
            $sqlPostTag = "INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)";
            $stmtPostTag = $this->db->prepare($sqlPostTag);
            $stmtPostTag->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmtPostTag->bindParam(':tag_id', $tagId, PDO::PARAM_INT);
            if (!$stmtPostTag->execute()) {
                $this->msg = "<div class='alert alert-danger'>Failed to add tags.</div>";
                return false;
            }
        }

        $this->msg = "<div class='alert alert-success'>Post created successfully.</div>";
        return true;
    }
}
