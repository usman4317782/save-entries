<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class UploadNewResearch {
    private $db;
    public $title, $description, $fileName, $fileType, $category, $visibility, $fileNameForUser;
    public $keywords, $authors, $publication_date, $citation_metrics; // New properties
    public $message; // Property to hold messages

    public function __construct() {
        $this->db = Database::getInstance();
        $this->message = ''; // Initialize message
    }

    public function uploadResearch($data, $userId) {
        if (isset($data['upload_new_research']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate inputs
            $this->title = $this->sanitizeInput($data['title']);
            $this->description = $this->sanitizeInput($data['description']);
            $this->fileNameForUser = $this->sanitizeInput($data['file_name']);
            $this->fileName = $_FILES['file_upload']['name']; // Keep original name for later use
            $this->fileType = $this->sanitizeInput($data['file_type']);
            $this->category = $this->sanitizeInput($data['category']);
            $this->visibility = $this->sanitizeInput($data['visibility']);
            $this->keywords = $this->sanitizeInput($data['keywords']); // New input
            $this->authors = $this->sanitizeInput($data['authors']); // New input
            $this->publication_date = $this->sanitizeInput($data['publication_date']); // New input
            $this->citation_metrics = $this->sanitizeInput($data['citation_metrics']); // New input

            // Check if all required fields are filled
            if (empty($this->title) || empty($this->fileName) || empty($this->fileType) || empty($this->category) || empty($this->visibility)) {
                $this->message = '<div class="alert alert-danger">All fields are required.</div>';
                return $this->message;
            }

            // Handle file upload securely
            $targetDir = BASE_PATH . "/uploads/research_docs";
            $fileExtension = pathinfo($this->fileName, PATHINFO_EXTENSION); // Get the file extension
            $uniqueKey = uniqid(); // Generate a unique key
            $uniqueFileName = $this->title . '_' . $uniqueKey . '.' . $fileExtension; // Use actual title and unique key
            $targetFile = $targetDir . '/' . $uniqueFileName; // Create the full path for the file

            if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $targetFile)) {
                // Calculate file size
                $fileSize = filesize($targetFile); // Get the size of the uploaded file

                // Insert into database
                if ($this->insertResearch($userId, $targetFile, $fileSize)) {
                    $this->message = '<div class="alert alert-success">Research uploaded successfully!</div>';
                } else {
                    $this->message = '<div class="alert alert-danger">Error inserting data into the database.</div>';
                }
            } else {
                $this->message = '<div class="alert alert-danger">Error uploading file.</div>';
            }
        }
        return $this->message; // Return the message for display
    }

    private function sanitizeInput($input) {
        // Trim whitespace
        $input = trim($input);
        
        // Remove HTML tags
        $input = strip_tags($input);
        
        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        return $input;
    }

    private function insertResearch($userId, $filePath, $fileSize) {
        // Prepare the SQL statement using PDO
        $stmt = $this->db->getConnection()->prepare("INSERT INTO resources (user_id, title, description, file_name, file_name_for_user, file_path, file_type, category, visibility, file_size, keywords, authors, publication_date, citation_metrics) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Execute the statement with the bound parameters
        return $stmt->execute([$userId, $this->title, $this->description, basename($filePath), $this->fileNameForUser, $filePath, $this->fileType, $this->category, $this->visibility, $fileSize, $this->keywords, $this->authors, $this->publication_date, $this->citation_metrics]);
    }
}