<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class CommunityGuidelines
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateGuideline($id, $data)
    {
        $title = $data['title'];
        $description = $data['description'];
        $sql = "UPDATE community_guidelines SET title = :title, description = :description WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function fetchGuideline($id)
    {
        // $id = 2;
        $sql = "SELECT * FROM community_guidelines WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
