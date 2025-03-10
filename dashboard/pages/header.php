<?php
include __DIR__ . "/../../config/config.php";

session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Get the current script name
$currentScript = basename($_SERVER['SCRIPT_NAME']);
$pageTitle = "SAVEENT - Dashboard"; // Default title

// First check if it's a specific php file
switch ($currentScript) {
    case 'sales.php':
        $pageTitle = "SAVEENT - Sales Management";
        break;
    case 'view_invoice.php':
        $pageTitle = "SAVEENT - Invoice Details";
        break;
    case 'products.php':
        $pageTitle = "SAVEENT - Product Management";
        break;
    case 'categories.php':
        $pageTitle = "SAVEENT - Category Management";
        break;
    case 'brands.php':
        $pageTitle = "SAVEENT - Brand Management";
        break;
    case 'customers.php':
        $pageTitle = "SAVEENT - Customer Management";
        break;
    case 'suppliers.php':
        $pageTitle = "SAVEENT - Supplier Management";
        break;
    case 'users.php':
        $pageTitle = "SAVEENT - User Management";
        break;
    case 'reports.php':
        $pageTitle = "SAVEENT - Reports";
        break;
    case 'settings.php':
        $pageTitle = "SAVEENT - Settings";
        break;
}

// Then check for page parameter if title hasn't been set by script name
if ($pageTitle === "SAVEENT - Dashboard" && isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'dashboard':
            $pageTitle = "SAVEENT - Dashboard Overview";
            break;
        case 'vendors':
            $pageTitle = "SAVEENT - Vendor Management";
            break;
        case 'customers':
            $pageTitle = "SAVEENT - Customer Management";
            break;
        case 'orders':
            $pageTitle = "SAVEENT - Order Management";
            break;
        case 'inventory':
            $pageTitle = "SAVEENT - Inventory Management";
            break;
        case 'transactions':
            $pageTitle = "SAVEENT - Transaction History";
            break;
        case 'analytics':
            $pageTitle = "SAVEENT - Analytics & Reports";
            break;
        case 'profile':
            $pageTitle = "SAVEENT - User Profile";
            break;
    }
}

// For invoice view, append invoice number if available
if ($currentScript === 'view_invoice.php' && isset($_GET['id'])) {
    $invoiceId = htmlspecialchars($_GET['id']);
    $pageTitle = "SAVEENT - Invoice #" . $invoiceId;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle; ?></title> <!-- Dynamic Title -->
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="inc/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="inc/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="inc/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="inc/css/style.css" rel="stylesheet">

    <!-- New Link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Your dashboard content follows here -->
</body>

</html>