<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class UpdateResearch
{
    private $db;
    public $title, $description, $fileName, $fileType, $category, $visibility, $fileNameForUser;
    public $keywords, $authors, $publicationDate, $citationMetrics; // New properties for additional fields
    public $message; // Property to hold messages

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->message = ''; // Initialize message
    }

    public function updateResearch($data, $userId, $researchRecordId)
    {
        if (isset($data['update_new_research']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate inputs
            $this->title = $this->sanitizeInput($data['title']);
            $this->description = $this->sanitizeInput($data['description']);
            $this->fileNameForUser = $this->sanitizeInput($data['file_name']);
            $this->fileType = $this->sanitizeInput($data['file_type']);
            $this->category = $this->sanitizeInput($data['category']);
            $this->visibility = $this->sanitizeInput($data['visibility']);
            $this->keywords = $this->sanitizeInput($data['keywords']);
            $this->authors = $this->sanitizeInput($data['authors']);
            $this->publicationDate = $this->sanitizeInput($data['publication_date']);
            $this->citationMetrics = isset($data['citation_metrics']) ? (int)$data['citation_metrics'] : 0;

            // Check if all required fields are filled
            if (empty($this->title) || empty($this->description) || empty($this->category) || empty($this->keywords) || empty($this->authors) || empty($this->publicationDate) || empty($this->visibility ) || empty($this->citationMetrics)) {
                $this->message = '<div class="alert alert-danger">All fields are required.</div>';
                return $this->message;
            }

            // Handle file upload securely
            $targetDir = BASE_PATH . "/uploads/research_docs";
            $oldFilePath = $this->getOldFilePath($userId, $researchRecordId); // Get the old file path from the database
            $newFileUploaded = isset($_FILES['file_upload']['name']) && !empty($_FILES['file_upload']['name']);

            if ($newFileUploaded) {
                // Delete the old file if it exists
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Delete the old file
                }

                $this->fileName = $_FILES['file_upload']['name']; // Keep original name for later use
                $fileExtension = pathinfo($this->fileName, PATHINFO_EXTENSION); // Get the file extension
                $uniqueKey = uniqid(); // Generate a unique key
                $uniqueFileName = $this->title . '_' . $uniqueKey . '.' . $fileExtension; // Use actual title and unique key
                $targetFile = $targetDir . '/' . $uniqueFileName; // Create the full path for the file

                if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $targetFile)) {
                    // Calculate file size
                    $fileSize = filesize($targetFile); // Get the size of the uploaded file
                } else {
                    $this->message = '<div class="alert alert-danger">Error uploading file.</div>';
                    return $this->message;
                }
            } else {
                // Retain the old file details
                $this->fileName = basename($oldFilePath); // Use the old file name
                $fileSize = $this->getOldFileSize($userId, $researchRecordId); // Get the old file size from the database
                $targetFile = $oldFilePath; // Keep the old file path
            }

            // Update the existing record in the database
            if ($this->updateResearchRecord($userId, $researchRecordId, $targetFile, $fileSize)) {
                $this->message = '<div class="alert alert-success">Research updated successfully!</div>';
            } else {
                $this->message = '<div class="alert alert-danger">Error updating data in the database.</div>';
            }
        }
        return $this->message; // Return the message for display
    }

    private function sanitizeInput($input)
    {
        // Trim whitespace
        $input = trim($input);

        // Remove HTML tags
        $input = strip_tags($input);

        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        return $input;
    }

    private function updateResearchRecord($userId, $researchRecordId, $filePath, $fileSize)
    {
        // Prepare the SQL statement using PDO for updating the existing record
        $stmt = $this->db->getConnection()->prepare("UPDATE resources SET title=?, description=?, file_name=?, file_name_for_user=?, file_path=?, file_type=?, category=?, visibility=?, file_size=?, keywords=?, authors=?, publication_date=?, citation_metrics=? WHERE user_id=? AND id=?");

        // Execute the statement with the bound parameters
        return $stmt->execute([
            $this->title,
            $this->description,
            basename($filePath),
            $this->fileNameForUser,
            $filePath,
            $this->fileType,
            $this->category,
            $this->visibility,
            $fileSize,
            $this->keywords,
            $this->authors,
            $this->publicationDate,
            $this->citationMetrics,
            $userId,
            $researchRecordId
        ]);
    }

    private function getOldFilePath($userId, $researchRecordId)
    {
        // Logic to retrieve the old file path from the database based on userId and researchRecordId
        $stmt = $this->db->getConnection()->prepare("SELECT file_path FROM resources WHERE user_id = ? AND id = ? LIMIT 1");
        $stmt->execute([$userId, $researchRecordId]);
        return $stmt->fetchColumn();
    }

    private function getOldFileSize($userId, $researchRecordId)
    {
        // Logic to retrieve the old file size from the database based on userId and researchRecordId
        $stmt = $this->db->getConnection()->prepare("SELECT file_size FROM resources WHERE user_id = ? AND id = ? LIMIT 1");
        $stmt->execute([$userId, $researchRecordId]);
        return $stmt->fetchColumn();
    }
}
