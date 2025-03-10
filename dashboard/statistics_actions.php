<?php
require_once '../classes/AllStatistics.php';

$statistics = new AllStatistics();
$action = $_GET['action'] ?? '';

if ($action === 'fetch_statistics') {
    $stats = [
        'total_brands' => $statistics->getTotalBrands(),
        'total_categories' => $statistics->getTotalCategories(),
        'total_products' => $statistics->getTotalProducts(),
        'total_customers' => $statistics->getTotalCustomers(),
        'total_users' => $statistics->getTotalUsers(),
        'total_vendors' => $statistics->getTotalVendors(),
        'total_sales' => $statistics->getTotalSales(),
        'total_revenue' => $statistics->getTotalRevenue(),
        'products_per_brand' => $statistics->getProductsPerBrand(),
        'products_per_category' => $statistics->getProductsPerCategory(),
        'total_stock_quantity' => $statistics->getTotalStockQuantity(),
        'total_product_value' => $statistics->getTotalProductValue(),
        'average_product_price' => $statistics->getAverageProductPrice(),
        'active_users' => $statistics->getActiveUsers(),
        'verified_users' => $statistics->getVerifiedUsers(),
        'sales_trends' => $statistics->getSalesTrends(),
    ];
    echo json_encode(["success" => true, "data" => $stats]);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid action"]);
?>