<?php
require_once '../classes/Customer.php';

$customer = new Customer();
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';

    // Fetch customers with filters
    $customers = $customer->getCustomers($search, $startDate, $endDate);
    
    // Ensure proper JSON format for both DataTables and Select2
    $response = [
        "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
        "recordsTotal" => count($customers),
        "recordsFiltered" => count($customers),
        "data" => array_map(function($customer) {
            // Convert all values to appropriate types for JSON
            return array_map(function($value) {
                if (is_numeric($value)) {
                    return $value * 1; // Convert to number
                }
                return $value ?? ""; // Convert null to empty string
            }, $customer);
        }, $customers)
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($action === 'fetch_single') {
    $id = $_GET['id'] ?? 0;
    $cust = $customer->getCustomerById($id);
    echo json_encode($cust);
    exit;
}

if ($action === 'add') {
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    if ($customer->isDuplicate($contact_number)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate contact number found."]);
        exit;
    }

    if ($customer->createCustomer($name, $address, $contact_number)) {
        echo json_encode(["success" => true, "message" => "Customer added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add customer"]);
    }
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';

    if ($customer->isDuplicate($contact_number, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate contact number found."]);
        exit;
    }

    if ($customer->updateCustomer($id, $name, $address, $contact_number)) {
        echo json_encode(["success" => true, "message" => "Customer updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update customer"]);
    }
    exit;
}

if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids)) {
        if ($customer->bulkDelete($ids)) {
            echo json_encode(["success" => true, "message" => "Selected customers deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete customers"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No customers selected"]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>