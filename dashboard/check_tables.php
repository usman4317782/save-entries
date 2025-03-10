<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once '../classes/Database.php';

try {
    // Create database connection
    $db = new Database();
    $conn = $db->getConnection();
    
    // Tables to check
    $tables = [
        'brands',
        'categories',
        'products',
        'customers',
        'users',
        'vendors',
        'sales',
        'sale_items',
        'purchases',
        'purchase_items'
    ];
    
    echo "<h1>Database Table Check</h1>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Table Name</th><th>Exists</th><th>Row Count</th></tr>";
    
    foreach ($tables as $table) {
        // Check if table exists
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        $exists = $stmt->rowCount() > 0;
        
        // Get row count if table exists
        $rowCount = 0;
        if ($exists) {
            $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM $table");
            $countStmt->execute();
            $rowCount = $countStmt->fetch()['count'];
        }
        
        // Output result
        echo "<tr>";
        echo "<td>$table</td>";
        echo "<td>" . ($exists ? "Yes" : "No") . "</td>";
        echo "<td>" . ($exists ? $rowCount : "N/A") . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Check database connection details
    echo "<h2>Database Connection Details</h2>";
    echo "<p>Host: " . DB_HOST . "</p>";
    echo "<p>Database: " . DB_DATABASE . "</p>";
    echo "<p>Username: " . DB_USERNAME . "</p>";
    echo "<p>Password: " . (empty(DB_PASSWORD) ? "Empty" : "Set") . "</p>";
    
    // Check PHP version and extensions
    echo "<h2>PHP Information</h2>";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>";
    echo "<p>PDO Enabled: " . (extension_loaded('pdo') ? "Yes" : "No") . "</p>";
    echo "<p>PDO MySQL Enabled: " . (extension_loaded('pdo_mysql') ? "Yes" : "No") . "</p>";
    
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Trace: <pre>" . $e->getTraceAsString() . "</pre></p>";
}
?> 