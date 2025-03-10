<?php
require_once '../classes/Category.php';

$category = new Category();
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $type = $_GET['type'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';

    // Fetch categories with filters
    $categories = $category->getCategories($search, $type, $startDate, $endDate);
    echo json_encode(["data" => $categories]);
    exit;
}

if ($action === 'fetch_single') {
    $id = $_GET['id'] ?? 0;
    $cat = $category->getCategoryById($id);
    echo json_encode($cat);
    exit;
}

if ($action === 'add') {
    $category_name = $_POST['category_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $type = $_POST['type'] ?? '';

    if ($category->isDuplicate($category_name)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    if ($category->createCategory($category_name, $description, $type)) {
        echo json_encode(["success" => true, "message" => "Category added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add category"]);
    }
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $category_name = $_POST['category_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $type = $_POST['type'] ?? '';

    if ($category->isDuplicate($category_name, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    if ($category->updateCategory($id, $category_name, $description, $type)) {
        echo json_encode(["success" => true, "message" => "Category updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update category"]);
    }
    exit;
}

if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids)) {
        if ($category->bulkDelete($ids)) {
            echo json_encode(["success" => true, "message" => "Selected categories deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete categories"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No categories selected"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>