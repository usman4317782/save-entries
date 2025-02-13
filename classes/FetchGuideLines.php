<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchGuideLines
{
    private $db;
    public $msg;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllGuidelines()
    {
        $sql = "SELECT * FROM community_guidelines";

        $stmt = $this->db->prepare($sql);
        if (!$stmt->execute()) {
            $this->msg = "<div class='alert alert-danger'>Failed to fetch guidelines.</div>";
            return false;
        }

        $guidelines = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $guidelines;
    }

}
