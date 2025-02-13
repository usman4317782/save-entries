<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchResearchRecordById {
    private $db;
    public $title, $description, $fileName, $fileType, $category, $visibility, $keywords, $authors, $publication_date, $citation_metrics;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function fetchResearchRecord($id, $userId) {
        $sql = "SELECT * FROM resources WHERE id = :id AND user_id = :userId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record) {
            $this->title = $record['title'];
            $this->description = $record['description'];
            $this->fileName = $record['file_name_for_user'];
            $this->fileType = $record['file_type'];
            $this->category = $record['category'];
            $this->visibility = $record['visibility'];
            $this->keywords = $record['keywords'];
            $this->authors = $record['authors'];
            $this->publication_date = $record['publication_date'];
            $this->citation_metrics = $record['citation_metrics'];
        } else {
            return false; // Record not found
        }
    }

}
