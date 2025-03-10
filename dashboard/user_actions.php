<?php
require_once '../classes/User.php';

$user = new User();
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'fetch':
            // DataTables server-side parameters
            $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
            
            // Search parameters
            $search = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
            $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
            $end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';

            // Get records
            $total_records = $user->getTotalRecords();
            $filtered_records = $user->getFilteredRecords($search, $start_date, $end_date);
            $data = $user->getUsers($search, $start_date, $end_date, $start, $length);

            echo json_encode([
                "draw" => $draw,
                "recordsTotal" => $total_records,
                "recordsFiltered" => $filtered_records,
                "data" => $data
            ]);
            break;

        case 'fetch_single':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id <= 0) {
                throw new Exception("Invalid user ID");
            }

            $userData = $user->getUserById($id);
            if (!$userData) {
                throw new Exception("User not found");
            }

            echo json_encode($userData);
            break;

        case 'add':
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');

            // Validate required fields
            if (empty($username) || empty($email) || empty($password)) {
                throw new Exception("Username, email, and password are required");
            }

            // Validate username format
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                throw new Exception("Username can only contain letters, numbers, and underscores");
            }

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            if ($user->createUser($username, $email, $password, $first_name, $last_name)) {
                echo json_encode([
                    "success" => true,
                    "message" => "User added successfully"
                ]);
            } else {
                throw new Exception("Failed to add user");
            }
            break;

        case 'update':
            $id = intval($_POST['id'] ?? 0);
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');

            // Validate required fields
            if (empty($username) || empty($email)) {
                throw new Exception("Username and email are required");
            }

            // Validate username format
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                throw new Exception("Username can only contain letters, numbers, and underscores");
            }

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            if ($user->updateUser($id, $username, $email, $password, $first_name, $last_name)) {
                echo json_encode([
                    "success" => true,
                    "message" => "User updated successfully"
                ]);
            } else {
                throw new Exception("Failed to update user");
            }
            break;

        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception("Invalid user ID");
            }

            if ($user->deleteUser($id)) {
                echo json_encode([
                    "success" => true,
                    "message" => "User deleted successfully"
                ]);
            } else {
                throw new Exception("Failed to delete user");
            }
            break;

        case 'bulk_delete':
            $ids = $_POST['ids'] ?? [];
            if (empty($ids)) {
                throw new Exception("No users selected");
            }

            if ($user->bulkDelete($ids)) {
                echo json_encode([
                    "success" => true,
                    "message" => "Selected users deleted successfully"
                ]);
            } else {
                throw new Exception("Failed to delete users");
            }
            break;

        default:
            throw new Exception("Invalid action");
    }
} catch (Exception $e) {
    error_log("Error in user_actions.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>