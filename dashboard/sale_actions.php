<?php
include __DIR__ . "/../config/config.php";
include __DIR__ . "/../classes/Sale.php";

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

    $sale = new Sale($db, $GLOBALS['logger']);
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    // Debug logging - use error_log instead of direct output
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
            
            error_log("Customers found: " . print_r($customers, true));
            
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
            
            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
            exit;

        case 'fetch':
            $search = $_GET['search'] ?? '';
            $customerId = $_GET['customer_id'] ?? '';
            $startDate = $_GET['start_date'] ?? '';
            $endDate = $_GET['end_date'] ?? '';

            $query = "SELECT s.id as sale_id, s.*, c.name as customer_name 
                     FROM sales s 
                     LEFT JOIN customers c ON s.customer_id = c.customer_id 
                     WHERE 1=1";
            $params = [];

            if ($search) {
                $query .= " AND (s.invoice_number LIKE :search OR c.name LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if ($customerId) {
                $query .= " AND s.customer_id = :customer_id";
                $params[':customer_id'] = $customerId;
            }

            if ($startDate) {
                $query .= " AND DATE(s.sale_date) >= :start_date";
                $params[':start_date'] = $startDate;
            }

            if ($endDate) {
                $query .= " AND DATE(s.sale_date) <= :end_date";
                $params[':end_date'] = $endDate;
            }

            $query .= " ORDER BY s.sale_date DESC";

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format data for DataTables
            foreach ($sales as &$row) {
                foreach ($row as $key => $value) {
                    if (is_numeric($value)) {
                        $row[$key] = floatval($value);
                    } else if ($value === null) {
                        $row[$key] = "";
                    } else {
                        $row[$key] = strval($value);
                    }
                }
            }

            echo json_encode([
                'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
                'recordsTotal' => count($sales),
                'recordsFiltered' => count($sales),
                'data' => $sales ?: []
            ]);
            exit;

        case 'get_sale':
            $saleId = $_GET['id'] ?? null;
            if (!$saleId) {
                echo json_encode(['status' => 'error', 'message' => 'Sale ID is required']);
                exit;
            }

            $saleData = $sale->getSaleById($saleId);
            if ($saleData) {
                // Format numbers
                $saleData['total_amount'] = floatval($saleData['total_amount']);
                $saleData['tax'] = floatval($saleData['tax']);
                $saleData['final_amount'] = floatval($saleData['final_amount']);
                
                foreach ($saleData['items'] as &$item) {
                    $item['quantity'] = floatval($item['quantity']);
                    $item['unit_price'] = floatval($item['unit_price']);
                    $item['discount'] = floatval($item['discount']);
                    $item['total_price'] = floatval($item['total_price']);
                }
                
                foreach ($saleData['payments'] as &$payment) {
                    $payment['amount'] = floatval($payment['amount']);
                }

                echo json_encode(['status' => 'success', 'data' => $saleData]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch sale details']);
            }
            exit;

        case 'update_sale':
            $saleId = $_POST['sale_id'];
            $data = [
                'customer_id' => $_POST['customer_id'],
                'sale_date' => $_POST['sale_date'],
                'total_amount' => $_POST['total_amount'],
                'discount' => $_POST['discount'] ?? 0,
                'tax' => $_POST['tax'] ?? 0,
                'final_amount' => $_POST['final_amount'],
                'payment_method' => $_POST['payment_method'],
                'notes' => $_POST['notes'] ?? null,
                'items' => json_decode($_POST['items'], true)
            ];

            // Add payment if provided
            if (!empty($_POST['payment_amount'])) {
                $data['payment'] = [
                    'amount' => $_POST['payment_amount'],
                    'payment_method' => $_POST['payment_method'],
                    'payment_date' => date('Y-m-d H:i:s'),
                    'transaction_id' => $_POST['transaction_id'] ?? null,
                    'notes' => $_POST['payment_notes'] ?? null,
                    'created_by' => $_SESSION['user_id']
                ];
            }

            if ($sale->updateSale($saleId, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Sale updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update sale']);
            }
            exit;

        case 'delete_sale':
            $saleId = $_POST['sale_id'];
            if ($sale->deleteSale($saleId)) {
                echo json_encode(['status' => 'success', 'message' => 'Sale deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete sale']);
            }
            exit;

        case 'bulk_delete':
            if (!isset($_POST['sale_ids']) || !is_array($_POST['sale_ids']) || empty($_POST['sale_ids'])) {
                echo json_encode(['status' => 'error', 'message' => 'No sales selected for deletion']);
                exit;
            }

            $result = $sale->bulkDeleteSales($_POST['sale_ids']);
            echo json_encode($result);
            exit;

        case 'create_sale':
            $data = [
                'invoice_number' => generateInvoiceNumber(),
                'customer_id' => $_POST['customer_id'],
                'sale_date' => $_POST['sale_date'],
                'total_amount' => $_POST['total_amount'],
                'discount' => $_POST['discount'] ?? 0,
                'tax' => $_POST['tax'] ?? 0,
                'final_amount' => $_POST['final_amount'],
                'payment_status' => 'pending',
                'payment_method' => $_POST['payment_method'],
                'notes' => $_POST['notes'] ?? null,
                'created_by' => $_SESSION['user_id'],
            ];
            
            // Decode items JSON and ensure it's an array
            $items = json_decode($_POST['items'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("JSON decode error: " . json_last_error_msg());
                echo json_encode(['status' => 'error', 'message' => 'Invalid items data']);
                exit;
            }
            
            $data['items'] = $items;

            // Add initial payment if provided
            if (!empty($_POST['payment_amount'])) {
                $data['payment'] = [
                    'amount' => $_POST['payment_amount'],
                    'payment_method' => $_POST['payment_method'],
                    'payment_date' => date('Y-m-d H:i:s'),
                    'transaction_id' => $_POST['transaction_id'] ?? null,
                    'notes' => $_POST['payment_notes'] ?? null
                ];
            }

            $saleId = $sale->createSale($data);
            if ($saleId) {
                echo json_encode(['status' => 'success', 'message' => 'Sale created successfully', 'sale_id' => $saleId]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create sale']);
            }
            exit;

        case 'get_sale_items':
            $saleId = $_POST['sale_id'];
            $items = $sale->getSaleItems($saleId);
            if ($items !== false) {
                echo json_encode(['status' => 'success', 'data' => $items]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch sale items']);
            }
            exit;

        case 'get_payments':
            $saleId = $_POST['sale_id'];
            $payments = $sale->getPayments($saleId);
            if ($payments !== false) {
                echo json_encode(['status' => 'success', 'data' => $payments]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch payments']);
            }
            exit;

        case 'add_payment':
            $data = [
                'sale_id' => $_POST['sale_id'],
                'amount' => $_POST['amount'],
                'payment_method' => $_POST['payment_method'],
                'payment_date' => date('Y-m-d H:i:s'),
                'transaction_id' => $_POST['transaction_id'] ?? null,
                'notes' => $_POST['notes'] ?? null,
                'created_by' => $_SESSION['user_id']
            ];

            if ($sale->addPayment($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Payment added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add payment']);
            }
            exit;

        case 'export_csv':
            // Get filters
            $filters = json_decode($_POST['filters'] ?? '{}', true);
            
            // Build query
            $query = "SELECT s.invoice_number, c.name as customer_name, c.contact_number, 
                     s.sale_date, s.total_amount, s.tax, s.final_amount, s.payment_status, 
                     s.payment_method, s.notes, GROUP_CONCAT(p.product_name) as products,
                     GROUP_CONCAT(si.quantity) as quantities,
                     GROUP_CONCAT(si.unit_price) as prices,
                     GROUP_CONCAT(si.discount) as discounts,
                     (SELECT SUM(amount) FROM payments WHERE sale_id = s.id) as paid_amount
                     FROM sales s 
                     LEFT JOIN customers c ON s.customer_id = c.customer_id 
                     LEFT JOIN sale_items si ON s.id = si.sale_id
                     LEFT JOIN products p ON si.product_id = p.id
                     WHERE 1=1";
            
            $params = [];
            
            if (!empty($filters['search'])) {
                $query .= " AND (s.invoice_number LIKE :search OR c.name LIKE :search)";
                $params[':search'] = "%{$filters['search']}%";
            }
            
            if (!empty($filters['customer_id'])) {
                $query .= " AND s.customer_id = :customer_id";
                $params[':customer_id'] = $filters['customer_id'];
            }
            
            if (!empty($filters['start_date'])) {
                $query .= " AND DATE(s.sale_date) >= :start_date";
                $params[':start_date'] = $filters['start_date'];
            }
            
            if (!empty($filters['end_date'])) {
                $query .= " AND DATE(s.sale_date) <= :end_date";
                $params[':end_date'] = $filters['end_date'];
            }
            
            $query .= " GROUP BY s.id ORDER BY s.sale_date DESC";
            
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="sales_export_' . date('Y-m-d_His') . '.csv"');
            
            // Create output handle
            $output = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($output, [
                'Invoice Number',
                'Customer Name',
                'Contact Number',
                'Sale Date',
                'Products',
                'Quantities',
                'Unit Prices',
                'Discounts',
                'Total Amount',
                'Tax',
                'Final Amount',
                'Paid Amount',
                'Payment Status',
                'Payment Method',
                'Notes'
            ]);
            
            // Add data
            foreach ($sales as $sale) {
                fputcsv($output, [
                    $sale['invoice_number'],
                    $sale['customer_name'],
                    $sale['contact_number'],
                    $sale['sale_date'],
                    $sale['products'],
                    $sale['quantities'],
                    $sale['prices'],
                    $sale['discounts'],
                    $sale['total_amount'],
                    $sale['tax'],
                    $sale['final_amount'],
                    $sale['paid_amount'] ?? 0,
                    $sale['payment_status'],
                    $sale['payment_method'],
                    $sale['notes']
                ]);
            }
            
            fclose($output);
            exit;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            exit;
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    exit;
}

function generateInvoiceNumber() {
    return 'INV-' . date('Ymd') . '-' . substr(uniqid(), -5);
}
