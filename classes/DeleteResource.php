<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class DeleteResource {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function deleteResource($id) {
        $successMessage = '';
        $errorMessage = '';

        // Fetch the file path before deletion
        $sql = "SELECT file_path FROM resources WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $filePath = $stmt->fetchColumn();

        // Delete the resource
        $sql = "DELETE FROM resources WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            // Delete the file from the local directory
            if ($filePath && file_exists($filePath)) {
                unlink($filePath);
            }
            $successMessage = 'Resource deleted successfully.';
        } else {
            $errorMessage = 'Error deleting resource. Please try again.';
        }

        return [
            'success' => $successMessage,
            'error' => $errorMessage,
            'rowCount' => $stmt->rowCount()
        ];
    }

   
}
