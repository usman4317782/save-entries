<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../classes/Product.php';
require_once '../classes/Brand.php';
require_once '../classes/Category.php';

header('Content-Type: application/json');
$product = new Product();
$brand = new Brand();
$category = new Category();
$action = $_GET['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $category_id = $_GET['category_id'] ?? '';
    $brand_id = $_GET['brand_id'] ?? '';
    $minPrice = $_GET['minPrice'] ?? '';
    $maxPrice = $_GET['maxPrice'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $stock_status = $_GET['stock_status'] ?? '';

    try {
        $products = $product->getProducts($search, $category_id, $brand_id, $minPrice, $maxPrice, $startDate, $endDate, $stock_status);
        echo json_encode(["data" => $products]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Server error: " . $e->getMessage()]);
    }
    exit;
}



if ($action === 'fetch_single') {
    $id = $_GET['id'] ?? 0;
    $prd = $product->getProductById($id);
    echo json_encode($prd);
    exit;
}

if ($action === 'add') {
    $category_id = $_POST['category_id'] ?? null;
    $brand_id = $_POST['brand_id'] ?? null;
    $product_name = $_POST['product_name'] ?? '';
    $description = $_POST['description'] ?? null;
    $price = $_POST['price'] ? floatval($_POST['price']) : null;
    $stock_quantity = $_POST['stock_quantity'] ? intval($_POST['stock_quantity']) : null;
    $sku = $_POST['sku'] ?? null;
    $stock_status = $_POST['stock_status'] ?? 'Non Stock';

    if (empty($product_name)) {
        echo json_encode(["success" => false, "message" => "Product name is required"]);
        exit;
    }

    if ($product->isDuplicate($category_id, $brand_id, $product_name)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    if ($product->createProduct($category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status)) {
        echo json_encode(["success" => true, "message" => "Product added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add product"]);
    }
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $category_id = $_POST['category_id'] ?? null;
    $brand_id = $_POST['brand_id'] ?? null;
    $product_name = $_POST['product_name'] ?? '';
    $description = $_POST['description'] ?? null;
    $price = $_POST['price'] ? floatval($_POST['price']) : null;
    $stock_quantity = $_POST['stock_quantity'] ? intval($_POST['stock_quantity']) : null;
    $sku = $_POST['sku'] ?? null;
    $stock_status = $_POST['stock_status'] ?? 'Non Stock';

    if (empty($product_name)) {
        echo json_encode(["success" => false, "message" => "Product name is required"]);
        exit;
    }

    if ($product->isDuplicate($category_id, $brand_id, $product_name, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    if ($product->updateProduct($id, $category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status)) {
        echo json_encode(["success" => true, "message" => "Product updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update product"]);
    }
    exit;
}

if ($action === 'bulk_delete') {
    $ids = $_POST['ids'] ?? [];
    if (!empty($ids)) {
        if ($product->bulkDelete($ids)) {
            echo json_encode(["success" => true, "message" => "Selected products deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete products"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No products selected"]);
    }
    exit;
}

if ($action === 'fetch_brands') {
    $brands = $brand->getBrands();
    echo json_encode(["data" => $brands]);
    exit;
}

if ($action === 'fetch_categories') {
    $categories = $category->getCategories();
    echo json_encode(["data" => $categories]);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
