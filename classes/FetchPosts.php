<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchPosts
{
    private $db;
    public $msg;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllPosts()
    {
        $sql = "SELECT 
            p.post_id, 
            p.title, 
            p.content, 
            p.created_at AS post_created_at, 
            p.updated_at AS post_updated_at, 
            GROUP_CONCAT(t.name) AS tags, 
            u.username, 
            pr.profile_picture, 
            pr.bio, 
            pr.affiliations, 
            pr.research_interests, 
            pr.created_at AS profile_created_at, 
            pr.updated_at AS profile_updated_at,
            COALESCE(SUM(CASE WHEN v.vote_type = 'upvote' THEN 1 ELSE 0 END), 0) AS votes_up,
            COALESCE(SUM(CASE WHEN v.vote_type = 'downvote' THEN 1 ELSE 0 END), 0) AS votes_down,
            (SELECT GROUP_CONCAT(CONCAT(c.content, ' - ', u.username) SEPARATOR '; ') 
             FROM comments c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.post_id = p.post_id) AS replies
        FROM posts p
        LEFT JOIN post_tags pt ON p.post_id = pt.post_id
        LEFT JOIN tags t ON pt.tag_id = t.tag_id
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN profiles pr ON u.id = pr.user_id
        LEFT JOIN votes v ON p.post_id = v.post_id
        GROUP BY p.post_id, u.id, pr.id
        ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        if (!$stmt->execute()) {
            $this->msg = "<div class='alert alert-danger'>Failed to fetch posts.</div>";
            return false;
        }

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }


    public function getAllPostsByUserId($user_id)
    {
        $sql = "SELECT 
            p.post_id, 
            p.title, 
            p.content, 
            p.created_at AS post_created_at, 
            p.updated_at AS post_updated_at, 
            GROUP_CONCAT(t.name) AS tags, 
            u.username, 
            pr.profile_picture, 
            pr.bio, 
            pr.affiliations, 
            pr.research_interests, 
            pr.created_at AS profile_created_at, 
            pr.updated_at AS profile_updated_at,
            COALESCE(SUM(CASE WHEN v.vote_type = 'upvote' THEN 1 ELSE 0 END), 0) AS votes_up,
            COALESCE(SUM(CASE WHEN v.vote_type = 'downvote' THEN 1 ELSE 0 END), 0) AS votes_down,
            (SELECT GROUP_CONCAT(CONCAT(c.content, ' - ', u.username) SEPARATOR '; ') 
             FROM comments c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.post_id = p.post_id) AS replies
        FROM posts p
        LEFT JOIN post_tags pt ON p.post_id = pt.post_id
        LEFT JOIN tags t ON pt.tag_id = t.tag_id
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN profiles pr ON u.id = pr.user_id
        LEFT JOIN votes v ON p.post_id = v.post_id
        WHERE p.user_id = :user_id
        GROUP BY p.post_id, u.id, pr.id
        ORDER BY p.created_at DESC";


        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        if (!$stmt->execute()) {
            $this->msg = "<div class='alert alert-danger'>Failed to fetch posts.</div>";
            return false;
        }

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }
}
