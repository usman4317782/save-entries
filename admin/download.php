<?php
// download.php

require_once "../config.php";

if (isset($_GET['file'])) {
    $file_name = urldecode($_GET['file']);
    $file_path = "../uploads/research_docs/" . $file_name;

    if (file_exists($file_path)) {
        // Get file's MIME type for forcing download when needed
        $mime_type = mime_content_type($file_path);
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));
        
        // Send file content to the browser
        readfile($file_path);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid file request.";
}
?>
