<?php
include __DIR__ . "/../config/config.php";

session_start();
// Disable error display for AJAX responses
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    // Create database connection
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8mb4",
        DB_USERNAME,
        DB_PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_company_info':
            // Validate required fields
            $requiredFields = ['company_name', 'company_address', 'company_phone', 'company_email'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'All fields are required'
                    ]);
                    exit;
                }
            }

            // Get company settings ID (should be 1 as we only have one record)
            $stmt = $db->query("SELECT id FROM company_settings LIMIT 1");
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            $companyId = $company ? $company['id'] : null;

            if ($companyId) {
                // Update existing record
                $query = "UPDATE company_settings SET 
                         company_name = ?, 
                         company_address = ?, 
                         company_phone = ?, 
                         company_email = ? 
                         WHERE id = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    $_POST['company_name'],
                    $_POST['company_address'],
                    $_POST['company_phone'],
                    $_POST['company_email'],
                    $companyId
                ]);
            } else {
                // Insert new record
                $query = "INSERT INTO company_settings 
                         (company_name, company_address, company_phone, company_email) 
                         VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    $_POST['company_name'],
                    $_POST['company_address'],
                    $_POST['company_phone'],
                    $_POST['company_email']
                ]);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Company information updated successfully'
            ]);
            break;

        case 'upload_logo':
            if (!isset($_FILES['logo'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No logo file uploaded'
                ]);
                exit;
            }

            $file = $_FILES['logo'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid file type. Only JPG and PNG files are allowed.'
                ]);
                exit;
            }

            // Validate file size
            if ($file['size'] > $maxSize) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'File size too large. Maximum size is 2MB.'
                ]);
                exit;
            }

            // Create uploads directory if it doesn't exist
            $uploadDir = __DIR__ . "/../assets/images/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $filename = "company-logo.png";
            $uploadPath = $uploadDir . $filename;

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Update logo path in database
                $stmt = $db->prepare("UPDATE company_settings SET logo_path = ? WHERE id = (SELECT id FROM (SELECT id FROM company_settings LIMIT 1) AS temp)");
                $stmt->execute(["/assets/images/company-logo.png"]);

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Logo uploaded successfully'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to upload logo'
                ]);
            }
            break;

        case 'remove_logo':
            $logoPath = __DIR__ . "/../assets/images/company-logo.png";
            
            // Remove file if exists
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }

            // Update database
            $stmt = $db->prepare("UPDATE company_settings SET logo_path = NULL WHERE id = (SELECT id FROM (SELECT id FROM company_settings LIMIT 1) AS temp)");
            $stmt->execute();

            echo json_encode([
                'status' => 'success',
                'message' => 'Logo removed successfully'
            ]);
            break;

        case 'get_company_info':
            $stmt = $db->query("SELECT * FROM company_settings LIMIT 1");
            $companyInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($companyInfo) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $companyInfo
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No company information found'
                ]);
            }
            break;

        default:
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action'
            ]);
            break;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred'
    ]);
}
?> 