<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class UpdatePost
{
    private $db;
    public $msg;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Fetch post details including tags
    public function fetchPost($post_id, $user_id)
    {
        $sql = "
            SELECT 
                p.*, 
                GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
            FROM 
                posts p
            LEFT JOIN 
                post_tags pt ON p.post_id = pt.post_id
            LEFT JOIN 
                tags t ON pt.tag_id = t.tag_id
            WHERE 
                p.post_id = :post_id 
                AND p.user_id = :user_id
            GROUP BY 
                p.post_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update post content and handle tags
    public function updatePost($post_id, $user_id, $title, $content, $tags)
    {
        // Update post title and content
        $sql = "
            UPDATE posts 
            SET title = :title, content = :content 
            WHERE post_id = :post_id AND user_id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // First, remove the existing tags for this post
        $deleteTagsSql = "DELETE FROM post_tags WHERE post_id = :post_id";
        $stmt = $this->db->prepare($deleteTagsSql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();

        // Then, insert the new tags
        if (!empty($tags)) {
            $tagArray = explode(',', $tags); // Split tags by commas
            foreach ($tagArray as $tag) {
                $tag = trim($tag); // Remove any extra spaces
                if (!empty($tag)) {
                    // Insert each tag into the tags table (if not already existing)
                    $insertTagSql = "INSERT INTO tags (name) SELECT :name WHERE NOT EXISTS (SELECT 1 FROM tags WHERE name = :name)";
                    $stmt = $this->db->prepare($insertTagSql);
                    $stmt->bindParam(':name', $tag);
                    $stmt->execute();

                    // Now, link the tag to the post
                    $insertPostTagSql = "INSERT INTO post_tags (post_id, tag_id) SELECT :post_id, tag_id FROM tags WHERE name = :name";
                    $stmt = $this->db->prepare($insertPostTagSql);
                    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                    $stmt->bindParam(':name', $tag);
                    $stmt->execute();
                }
            }
        }
    }
}
