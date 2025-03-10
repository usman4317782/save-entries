<?php
require_once '../classes/Vendor.php';

$vendor = new Vendor();
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';

    // Fetch vendors with filters
    $vendors = $vendor->getVendors($search, $startDate, $endDate);
    echo json_encode(["data" => $vendors]);
    exit;
}

if ($action === 'fetch_single') {
    $id = $_GET['id'] ?? 0;
    $vend = $vendor->getVendorById($id);
    echo json_encode($vend);
    exit;
}

if ($action === 'add') {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    if ($vendor->isDuplicate($contact_number)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate contact number found."]);
        exit;
    }

    if ($vendor->createVendor($name, $address, $contact_number)) {
        echo json_encode(["success" => true, "message" => "Vendor added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add vendor"]);
    }
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    if ($vendor->isDuplicate($contact_number, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate contact number found."]);
        exit;
    }

    if ($vendor->updateVendor($id, $name, $address, $contact_number)) {
        echo json_encode(["success" => true, "message" => "Vendor updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update vendor"]);
    }
    exit;
}

if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids)) {
        if ($vendor->bulkDelete($ids)) {
            echo json_encode(["success" => true, "message" => "Selected vendors deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete vendors"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No vendors selected"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>