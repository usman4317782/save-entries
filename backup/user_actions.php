<?php
require_once '../classes/User.php';

$user = new User();
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $users = $user->getUsers($search, $startDate, $endDate);
    echo json_encode(["data" => $users]);
    exit;
}

if ($action === 'fetch_single') {
    $id = $_GET['id'] ?? 0;
    $usr = $user->getUserById($id);
    echo json_encode($usr);
    exit;
}

if ($action === 'add') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';

    if (empty($password)) {
        echo json_encode(["success" => false, "message" => "Password is required"]);
        exit;
    }

    if ($user->isDuplicate($username, $email)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate username or email found."]);
        exit;
    }

    if ($user->createUser($username, $email, $password, $first_name, $last_name)) {
        echo json_encode(["success" => true, "message" => "User added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add user"]);
    }
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';

    if ($user->isDuplicate($username, $email, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate username or email found."]);
        exit;
    }

    if ($user->updateUser($id, $username, $email, $password, $first_name, $last_name)) {
        echo json_encode(["success" => true, "message" => "User updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update user"]);
    }
    exit;
}

if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids)) {
        if ($user->bulkDelete($ids)) {
            echo json_encode(["success" => true, "message" => "Selected users deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete users"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No users selected"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>