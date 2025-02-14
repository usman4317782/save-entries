<?php

// require_once __DIR__ . '/../config.php';  // Ensure correct path

include __DIR__.'/../classes/Database.php'; //

// $db = Database::getInstance()->getConnection();

// $sql = "SELECT id, username, email, role, is_verified FROM users WHERE role != 'Admin'";
// $stmt = $db->prepare($sql);
// $stmt->execute();
// $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Set the header for JSON response
// header('Content-Type: application/json');
// echo json_encode($users);
// exit;

header('Content-Type: application/json'); // Ensure JSON response

try {
    $db = Database::getInstance()->getConnection();
    $sql = "SELECT id, username, email, role, is_verified FROM users WHERE role != 'Admin'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} catch (Exception $e) {
    echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
}


?>
