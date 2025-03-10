<?php
require_once '../classes/Brand.php';

$brand = new Brand();
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';

    // Fetch brands with filters
    $brands = $brand->getBrands($search, $startDate, $endDate);
    echo json_encode(["data" => $brands]);
    exit;
}

if ($action === 'fetch_single') {
    $id = $_GET['id'] ?? 0;
    $brd = $brand->getBrandById($id);
    echo json_encode($brd);
    exit;
}

if ($action === 'add') {
    $brand_name = $_POST['brand_name'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($brand->isDuplicate($brand_name)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    if ($brand->createBrand($brand_name, $description)) {
        echo json_encode(["success" => true, "message" => "Brand added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add brand"]);
    }
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $brand_name = $_POST['brand_name'] ?? '';
    $description = $_POST['description'] ?? '';

    if ($brand->isDuplicate($brand_name, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    if ($brand->updateBrand($id, $brand_name, $description)) {
        echo json_encode(["success" => true, "message" => "Brand updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update brand"]);
    }
    exit;
}

if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids)) {
        if ($brand->bulkDelete($ids)) {
            echo json_encode(["success" => true, "message" => "Selected brands deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete brands"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No brands selected"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>
