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
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'fetch') {
    $search = $_GET['search'] ?? '';
    $category_id = $_GET['category_id'] ?? '';
    $brand_id = $_GET['brand_id'] ?? '';
    $minPrice = $_GET['minPrice'] ?? '';
    $maxPrice = $_GET['maxPrice'] ?? '';
    $minCost = $_GET['minCost'] ?? '';
    $maxCost = $_GET['maxCost'] ?? '';
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $stock_status = $_GET['stock_status'] ?? '';

    try {
        $products = $product->getProducts($search, $category_id, $brand_id, $minPrice, $maxPrice, $startDate, $endDate, $stock_status, $minCost, $maxCost);
        echo json_encode(["data" => $products]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Server error: " . $e->getMessage()]);
    }
    exit;
}

if ($action === 'get_categories_and_brands') {
    try {
        $categories = $category->getCategories();
        $brands = $brand->getBrands();
        echo json_encode([
            "status" => "success",
            "categories" => $categories,
            "brands" => $brands
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to load categories and brands: " . $e->getMessage()
        ]);
    }
    exit;
}

if ($action === 'quick_add_product') {
    $category_id = $_POST['category_id'] ?? null;
    $brand_id = $_POST['brand_id'] ?? null;
    $product_name = $_POST['product_name'] ?? '';
    $description = $_POST['description'] ?? null;
    $price = $_POST['price'] ? floatval($_POST['price']) : null;
    $cost = $_POST['cost'] ? floatval($_POST['cost']) : null;
    $stock_quantity = $_POST['stock_quantity'] ? intval($_POST['stock_quantity']) : 0;
    $sku = $_POST['sku'] ?? null;
    $unique_id = $_POST['unique_id'] ?? null;
    $stock_status = $_POST['stock_status'] ?? 'Stock';

    if (empty($product_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Product name is required"
        ]);
        exit;
    }

    if ($product->isDuplicate($category_id, $brand_id, $product_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Error! Duplicate entry found."
        ]);
        exit;
    }

    // Check if unique_id is provided and not empty
    if (!empty($unique_id)) {
        // Check if unique_id already exists
        $query = "SELECT COUNT(*) as count FROM products WHERE unique_id = :unique_id";
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':unique_id', $unique_id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Error! Product Unique ID already exists."
            ]);
            exit;
        }
    }

    // Create the product with all fields
    if ($product->createProduct($category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status, $cost, $unique_id)) {
        // Get the newly created product details
        $newProduct = $product->getLastInsertedProduct();
        
        // Add brand name to the response if brand exists
        if ($brand_id) {
            $brandInfo = $brand->getBrandById($brand_id);
            $newProduct['brand_name'] = $brandInfo['brand_name'] ?? '';
        }

        echo json_encode([
            "status" => "success",
            "message" => "Product added successfully",
            "data" => $newProduct
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add product"
        ]);
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
    $cost = $_POST['cost'] ? floatval($_POST['cost']) : null;
    $stock_quantity = $_POST['stock_quantity'] ? intval($_POST['stock_quantity']) : null;
    $sku = $_POST['sku'] ?? null;
    $unique_id = $_POST['unique_id'] ?? null;
    $stock_status = $_POST['stock_status'] ?? 'Non Stock';

    if (empty($product_name)) {
        echo json_encode(["success" => false, "message" => "Product name is required"]);
        exit;
    }

    if ($product->isDuplicate($category_id, $brand_id, $product_name)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    // Check if unique_id is provided and not empty
    if (!empty($unique_id)) {
        // Check if unique_id already exists
        $query = "SELECT COUNT(*) as count FROM products WHERE unique_id = :unique_id";
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':unique_id', $unique_id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            echo json_encode(["success" => false, "message" => "Error! Product Unique ID already exists."]);
            exit;
        }
    }

    if ($product->createProduct($category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status, $cost, $unique_id)) {
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
    $cost = $_POST['cost'] ? floatval($_POST['cost']) : null;
    $stock_quantity = $_POST['stock_quantity'] ? intval($_POST['stock_quantity']) : null;
    $sku = $_POST['sku'] ?? null;
    $unique_id = $_POST['unique_id'] ?? null;
    $stock_status = $_POST['stock_status'] ?? 'Non Stock';

    if (empty($product_name)) {
        echo json_encode(["success" => false, "message" => "Product name is required"]);
        exit;
    }

    if ($product->isDuplicate($category_id, $brand_id, $product_name, $id)) {
        echo json_encode(["success" => false, "message" => "Error! Duplicate entry found."]);
        exit;
    }

    // Check if unique_id is provided and not empty
    if (!empty($unique_id)) {
        // Check if unique_id already exists (but not for this product)
        $query = "SELECT COUNT(*) as count FROM products WHERE unique_id = :unique_id AND id != :id";
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':unique_id', $unique_id, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            echo json_encode(["success" => false, "message" => "Error! Product Unique ID already exists."]);
            exit;
        }
    }

    if ($product->updateProduct($id, $category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status, $cost, $unique_id)) {
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
