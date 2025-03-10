<?php
require_once '../classes/Category.php';

$category = new Category();
$action = $_GET['action'] ?? '';

if ($action === 'export_pdf') {
    // Implement PDF export logic here
    // You can use libraries like TCPDF or FPDF
    echo "PDF export functionality to be implemented.";
    exit;
}

if ($action === 'export_csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="categories.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Category Name', 'Description', 'Type', 'Created At']);
    $categories = $category->getCategories(1000, 0); // Adjust limit as needed
    foreach ($categories as $cat) {
        fputcsv($output, $cat);
    }
    fclose($output);
    exit;
}

echo "Invalid export action";
?>