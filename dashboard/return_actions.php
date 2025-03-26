<?php
// Start output buffering to prevent any unwanted output
ob_start();

include __DIR__ . "/../config/config.php";
include __DIR__ . "/../classes/SaleReturn.php";

session_start();
// Disable error display for AJAX responses
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    // Create database connection
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";charset=utf8mb4",
        DB_USERNAME,
        DB_PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Initialize SaleReturn class - Logger is initialized inside SaleReturn
    $saleReturn = new SaleReturn($db, null);

    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    // Debug logging
    error_log("Action received: " . $action);
    error_log("GET params: " . print_r($_GET, true));
    error_log("POST params: " . print_r($_POST, true));

    switch ($action) {
        case 'get_customers':
            $search = $_GET['search'] ?? '';
            $query = "SELECT customer_id, name, contact_number FROM customers 
                     WHERE (name LIKE :search OR contact_number LIKE :search)
                     ORDER BY name ASC LIMIT 10";
            $stmt = $db->prepare($query);
            $stmt->execute([':search' => "%$search%"]);
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            ob_clean();
            echo json_encode([
                'status' => 'success',
                'data' => $customers
            ]);
            exit;

        case 'get_products':
            $search = $_GET['search'] ?? '';
            $query = "SELECT p.id, p.product_name, p.price, p.stock_quantity, p.description,
                     COALESCE(b.brand_name, '') as brand_name
                     FROM products p
                     LEFT JOIN brands b ON p.brand_id = b.id
                     WHERE (p.product_name LIKE :search OR p.description LIKE :search)
                     ORDER BY p.product_name ASC LIMIT 10";
            $stmt = $db->prepare($query);
            $stmt->execute([':search' => "%$search%"]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format numbers
            foreach ($products as &$product) {
                $product['price'] = floatval($product['price']);
                $product['stock_quantity'] = intval($product['stock_quantity']);
            }
            
            ob_clean();
            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
            exit;

        case 'fetch':
            try {
                error_log("Starting fetch action for returns");
                
                $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
                $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
                $length = isset($_GET['length']) ? intval($_GET['length']) : 10;
                $search = $_GET['search']['value'] ?? '';
                $customerId = $_GET['customer_id'] ?? '';
                $startDate = $_GET['start_date'] ?? '';
                $endDate = $_GET['end_date'] ?? '';
                
                error_log("Fetch parameters: draw=$draw, start=$start, length=$length, search=$search, customer=$customerId");
                
                // Base query for total records count
                $countQuery = "SELECT COUNT(*) as total FROM sales_returns";
                $totalStmt = $db->prepare($countQuery);
                
                try {
                    $result = $totalStmt->execute();
                    if (!$result) {
                        throw new Exception("Total count query failed: " . implode(" ", $totalStmt->errorInfo()));
                    }
                    $totalRecords = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
                    error_log("Total count: $totalRecords");
                } catch (Exception $e) {
                    error_log("Error executing total count query: " . $e->getMessage());
                    error_log("SQL Query: " . $countQuery);
                    throw $e;
                }

                // Main query with joins and conditions
                $query = "SELECT sr.id as return_id, sr.invoice_number, sr.return_date, 
                         sr.total_amount, sr.final_amount, c.name as customer_name
                         FROM sales_returns sr 
                         LEFT JOIN customers c ON sr.customer_id = c.customer_id 
                         WHERE 1=1";
                $params = [];

                if ($search) {
                    $query .= " AND (sr.invoice_number LIKE :search 
                              OR c.name LIKE :search)";
                    $params[':search'] = "%$search%";
                }

                if ($customerId) {
                    $query .= " AND sr.customer_id = :customer_id";
                    $params[':customer_id'] = $customerId;
                }

                if ($startDate) {
                    $query .= " AND DATE(sr.return_date) >= :start_date";
                    $params[':start_date'] = $startDate;
                }

                if ($endDate) {
                    $query .= " AND DATE(sr.return_date) <= :end_date";
                    $params[':end_date'] = $endDate;
                }

                // Get filtered records count
                $countFilteredQuery = str_replace("SELECT sr.id as return_id, sr.invoice_number", "SELECT COUNT(*) as total", $query);
                $countFilteredStmt = $db->prepare($countFilteredQuery);
                
                // Bind the same parameters for the filtered count
                foreach ($params as $key => $value) {
                    $countFilteredStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
                
                try {
                    $result = $countFilteredStmt->execute();
                    if (!$result) {
                        throw new Exception("Filtered count query failed: " . implode(" ", $countFilteredStmt->errorInfo()));
                    }
                    $filteredRecords = $countFilteredStmt->fetch(PDO::FETCH_ASSOC)['total'];
                    error_log("Filtered count: $filteredRecords");
                } catch (Exception $e) {
                    error_log("Error executing filtered count query: " . $e->getMessage());
                    error_log("SQL Query: " . $countFilteredQuery);
                    throw $e;
                }

                // Add sorting
                $orderColumn = isset($_GET['order'][0]['column']) ? intval($_GET['order'][0]['column']) : 1;
                $orderDir = isset($_GET['order'][0]['dir']) ? strtoupper($_GET['order'][0]['dir']) : 'DESC';
                
                // Define columns that can be sorted
                $columns = [
                    0 => 'sr.id',
                    1 => 'sr.invoice_number',
                    2 => 'c.name',
                    3 => 'sr.return_date',
                    4 => 'sr.total_amount',
                    5 => 'sr.final_amount'
                ];
                
                if (isset($columns[$orderColumn])) {
                    $query .= " ORDER BY " . $columns[$orderColumn] . " " . $orderDir;
                } else {
                    $query .= " ORDER BY sr.return_date DESC";
                }

                // Add pagination
                $query .= " LIMIT :start, :length";
                
                // Create a new statement
                $stmt = $db->prepare($query);
                
                // Bind parameters for search, filters
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
                
                // Explicitly bind the pagination parameters as integers
                $stmt->bindValue(':start', $start, PDO::PARAM_INT);
                $stmt->bindValue(':length', $length, PDO::PARAM_INT);
                
                // Execute the query with better error handling
                try {
                    $result = $stmt->execute();
                    if (!$result) {
                        throw new Exception("Query execution failed: " . implode(" ", $stmt->errorInfo()));
                    }
                    $returns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    error_log("Fetch query returned " . count($returns) . " results");
                } catch (Exception $e) {
                    error_log("Error executing fetch query: " . $e->getMessage());
                    error_log("SQL Query: " . $query);
                    throw $e;
                }

                // Format data for DataTables
                $data = [];
                foreach ($returns as $row) {
                    // Format numeric values
                    $totalAmount = number_format(floatval($row['total_amount']), 2);
                    $finalAmount = number_format(floatval($row['final_amount']), 2);
                    
                    // Format date
                    $returnDate = date('Y-m-d', strtotime($row['return_date']));
                    
                    // Add action buttons
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="view_return.php?id='.$row['return_id'].'" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>';
                    $actions .= '<a href="edit_return.php?id='.$row['return_id'].'" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="'.$row['return_id'].'" title="Delete"><i class="bi bi-trash"></i></button>';
                    $actions .= '</div>';
                    
                    $data[] = [
                        '<input type="checkbox" class="return-checkbox" value="'.$row['return_id'].'">',
                        htmlspecialchars($row['invoice_number']),
                        htmlspecialchars($row['customer_name']),
                        $returnDate,
                        $totalAmount,
                        $finalAmount,
                        $actions
                    ];
                }

                // At the end before returning JSON:
                $responseData = [
                    'draw' => $draw,
                    'recordsTotal' => intval($totalRecords),
                    'recordsFiltered' => intval($filteredRecords),
                    'data' => $data
                ];
                
                // Ensure we have clean JSON without any PHP errors
                $jsonResponse = json_encode($responseData);
                
                error_log("JSON response length: " . strlen($jsonResponse));
                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log("JSON encode error: " . json_last_error_msg());
                    // If there's a JSON encoding error, fall back to a simpler response
                    $jsonResponse = json_encode([
                        'draw' => $draw,
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => []
                    ]);
                }
                
                // Ensure no other output corrupts our JSON
                ob_clean();
                echo $jsonResponse;
            } catch (Exception $e) {
                error_log("Error in fetch action: " . $e->getMessage());
                
                // Clean any buffered output
                ob_clean();
                
                echo json_encode([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
            exit;

        case 'get_return':
            $returnId = $_GET['id'] ?? null;
            if (!$returnId) {
                echo json_encode(['status' => 'error', 'message' => 'Return ID is required']);
                exit;
            }

            $returnData = $saleReturn->getReturnById($returnId);
            if ($returnData) {
                // Format numbers
                $returnData['total_amount'] = floatval($returnData['total_amount']);
                $returnData['tax'] = floatval($returnData['tax']);
                $returnData['final_amount'] = floatval($returnData['final_amount']);
                
                foreach ($returnData['items'] as &$item) {
                    $item['quantity'] = floatval($item['quantity']);
                    $item['unit_price'] = floatval($item['unit_price']);
                    $item['discount'] = floatval($item['discount']);
                    $item['total_price'] = floatval($item['total_price']);
                }

                ob_clean();
                echo json_encode(['status' => 'success', 'data' => $returnData]);
            } else {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch return details']);
            }
            exit;

        case 'create_return':
            $data = [
                'invoice_number' => generateReturnNumber(),
                'customer_id' => $_POST['customer_id'],
                'return_date' => $_POST['return_date'],
                'total_amount' => $_POST['total_amount'],
                'discount' => $_POST['discount'] ?? 0,
                'tax' => $_POST['tax'] ?? 0,
                'final_amount' => $_POST['final_amount'],
                'payment_status' => 'pending',
                'payment_method' => $_POST['payment_method'],
                'notes' => $_POST['notes'] ?? null,
                'created_by' => $_SESSION['user_id'],
            ];
            
            // Decode items JSON
            $items = json_decode($_POST['items'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("JSON decode error: " . json_last_error_msg());
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Invalid items data']);
                exit;
            }
            
            $data['items'] = $items;

            $returnId = $saleReturn->createReturn($data);
            if ($returnId) {
                ob_clean();
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Return created successfully', 
                    'return_id' => $returnId
                ]);
            } else {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Failed to create return']);
            }
            exit;

        case 'delete_return':
            $returnId = $_POST['return_id'];
            if ($saleReturn->deleteReturn($returnId)) {
                ob_clean();
                echo json_encode(['status' => 'success', 'message' => 'Return deleted successfully']);
            } else {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete return']);
            }
            exit;

        case 'bulk_delete':
            if (!isset($_POST['return_ids']) || !is_array($_POST['return_ids']) || empty($_POST['return_ids'])) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'No returns selected for deletion']);
                exit;
            }

            $result = $saleReturn->bulkDeleteReturns($_POST['return_ids']);
            ob_clean();
            echo json_encode($result);
            exit;

        case 'update_return':
            try {
                // Enable error reporting for debugging
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                
                $returnId = $_POST['return_id'] ?? null;
                if (!$returnId) {
                    throw new Exception("Return ID is required");
                }
                
                error_log("Updating return ID: " . $returnId);
                
                // Validate items data
                $items = json_decode($_POST['items'] ?? '', true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($items)) {
                    error_log("JSON decode error: " . json_last_error_msg());
                    throw new Exception("Invalid items data");
                }
                
                $data = [
                    'customer_id' => $_POST['customer_id'] ?? null,
                    'return_date' => $_POST['return_date'] ?? null,
                    'total_amount' => $_POST['total_amount'] ?? 0,
                    'tax' => $_POST['tax'] ?? 0,
                    'final_amount' => $_POST['final_amount'] ?? 0,
                    'payment_method' => $_POST['payment_method'] ?? 'cash',
                    'notes' => $_POST['notes'] ?? null,
                    'items' => $items
                ];
                
                // Validate required fields
                if (!$data['customer_id']) {
                    throw new Exception("Customer ID is required");
                }
                if (!$data['return_date']) {
                    throw new Exception("Return date is required");
                }
                if (empty($data['items'])) {
                    throw new Exception("At least one item is required");
                }
                
                error_log("Update data: " . print_r($data, true));

                if ($saleReturn->updateReturn($returnId, $data)) {
                    ob_clean();
                    echo json_encode(['status' => 'success', 'message' => 'Return updated successfully']);
                } else {
                    throw new Exception("Failed to update return");
                }
            } catch (Exception $e) {
                error_log("Error in update_return: " . $e->getMessage());
                error_log("POST data: " . print_r($_POST, true));
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
            exit;

        default:
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            exit;
    }
} catch (PDOException $e) {
    error_log("PDOException: " . $e->getMessage());
    ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    ob_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    exit;
}

function generateReturnNumber() {
    return 'RET-' . date('Ymd') . '-' . substr(uniqid(), -5);
} 