<?php
require_once "config.php";
session_start();

// Database connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Update logout_time for the user
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $mysqli->prepare("UPDATE user_activity SET logout_time = NOW() WHERE user_id = ? AND logout_time IS NULL");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Destroy session
session_destroy();

if(isset($_SESSION['admin_id'])){
    header('Location: admin_login.php');
} elseif (isset($_SESSION['user_id'])) {
    header('Location: login.php');
}
