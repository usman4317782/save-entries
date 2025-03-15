<?php
include __DIR__ . "/../config/config.php";
include __DIR__ . "/../classes/Quotation.php";

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

    $quotation = new Quotation($db, $GLOBALS['logger']);
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

            $query = "SELECT q.id as quotation_id, q.*,
                     c.name as customer_name
                     FROM quotations q 
                     LEFT JOIN customers c ON q.customer_id = c.customer_id 
                     WHERE 1=1";
            $params = [];

            if ($search) {
                $query .= " AND (q.quotation_number LIKE :search OR c.name LIKE :search)";
                $params[':search'] = "%$search%";
            }

            if ($customerId) {
                $query .= " AND q.customer_id = :customer_id";
                $params[':customer_id'] = $customerId;
            }

            if ($startDate) {
                $query .= " AND DATE(q.quotation_date) >= :start_date";
                $params[':start_date'] = $startDate;
            }

            if ($endDate) {
                $query .= " AND DATE(q.quotation_date) <= :end_date";
                $params[':end_date'] = $endDate;
            }

            $query .= " ORDER BY q.quotation_date DESC";

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format data for DataTables
            foreach ($quotations as &$row) {
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
                'recordsTotal' => count($quotations),
                'recordsFiltered' => count($quotations),
                'data' => $quotations ?: []
            ]);
            exit;

        case 'get_quotation':
            $quotationId = $_GET['id'] ?? null;
            if (!$quotationId) {
                echo json_encode(['status' => 'error', 'message' => 'Quotation ID is required']);
                exit;
            }

            $quotationData = $quotation->getQuotationById($quotationId);
            if ($quotationData) {
                // Format numbers
                $quotationData['total_amount'] = floatval($quotationData['total_amount']);
                $quotationData['tax'] = floatval($quotationData['tax']);
                $quotationData['final_amount'] = floatval($quotationData['final_amount']);
                
                foreach ($quotationData['items'] as &$item) {
                    $item['quantity'] = floatval($item['quantity']);
                    $item['unit_price'] = floatval($item['unit_price']);
                    $item['discount'] = floatval($item['discount']);
                    $item['total_price'] = floatval($item['total_price']);
                }

                echo json_encode(['status' => 'success', 'data' => $quotationData]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch quotation details']);
            }
            exit;

        case 'update_quotation':
            $quotationId = $_POST['quotation_id'];
            $data = [
                'customer_id' => $_POST['customer_id'],
                'quotation_date' => $_POST['quotation_date'],
                'total_amount' => $_POST['total_amount'],
                'discount' => $_POST['discount'] ?? 0,
                'tax' => $_POST['tax'] ?? 0,
                'final_amount' => $_POST['final_amount'],
                'status' => $_POST['status'],
                'validity_period' => $_POST['validity_period'] ?? 30,
                'notes' => $_POST['notes'] ?? null,
                'items' => json_decode($_POST['items'], true)
            ];

            if ($quotation->updateQuotation($quotationId, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Quotation updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update quotation']);
            }
            exit;

        case 'delete_quotation':
            $quotationId = $_POST['quotation_id'];
            if ($quotation->deleteQuotation($quotationId)) {
                echo json_encode(['status' => 'success', 'message' => 'Quotation deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete quotation']);
            }
            exit;

        case 'bulk_delete':
            if (!isset($_POST['quotation_ids']) || !is_array($_POST['quotation_ids']) || empty($_POST['quotation_ids'])) {
                echo json_encode(['status' => 'error', 'message' => 'No quotations selected for deletion']);
                exit;
            }

            $result = $quotation->bulkDeleteQuotations($_POST['quotation_ids']);
            echo json_encode($result);
            exit;

        case 'create_quotation':
            $data = [
                'quotation_number' => generateQuotationNumber(),
                'customer_id' => $_POST['customer_id'],
                'quotation_date' => $_POST['quotation_date'],
                'total_amount' => $_POST['total_amount'],
                'discount' => $_POST['discount'] ?? 0,
                'tax' => $_POST['tax'] ?? 0,
                'final_amount' => $_POST['final_amount'],
                'status' => 'pending',
                'validity_period' => $_POST['validity_period'] ?? 30,
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

            $quotationId = $quotation->createQuotation($data);
            if ($quotationId) {
                echo json_encode(['status' => 'success', 'message' => 'Quotation created successfully', 'quotation_id' => $quotationId]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create quotation']);
            }
            exit;

        case 'get_quotation_items':
            $quotationId = $_POST['quotation_id'];
            $items = $quotation->getQuotationItems($quotationId);
            if ($items !== false) {
                echo json_encode(['status' => 'success', 'data' => $items]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to fetch quotation items']);
            }
            exit;

        case 'convert_to_sale':
            $quotationId = $_POST['quotation_id'];
            $saleId = $quotation->convertToSale($quotationId);
            if ($saleId) {
                echo json_encode(['status' => 'success', 'message' => 'Quotation converted to sale successfully', 'sale_id' => $saleId]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to convert quotation to sale']);
            }
            exit;

        case 'export_csv':
            // Get filters
            $filters = json_decode($_POST['filters'] ?? '{}', true);
            
            // Build query
            $query = "SELECT q.quotation_number, c.name as customer_name, c.contact_number, 
                     q.quotation_date, q.total_amount, q.tax, q.final_amount, q.status, 
                     q.validity_period, q.notes, GROUP_CONCAT(p.product_name) as products,
                     GROUP_CONCAT(qi.quantity) as quantities,
                     GROUP_CONCAT(qi.unit_price) as prices,
                     GROUP_CONCAT(qi.discount) as discounts
                     FROM quotations q 
                     LEFT JOIN customers c ON q.customer_id = c.customer_id 
                     LEFT JOIN quotation_items qi ON q.id = qi.quotation_id
                     LEFT JOIN products p ON qi.product_id = p.id
                     WHERE 1=1";
            
            $params = [];
            
            if (!empty($filters['search'])) {
                $query .= " AND (q.quotation_number LIKE :search OR c.name LIKE :search)";
                $params[':search'] = "%{$filters['search']}%";
            }
            
            if (!empty($filters['customer_id'])) {
                $query .= " AND q.customer_id = :customer_id";
                $params[':customer_id'] = $filters['customer_id'];
            }
            
            if (!empty($filters['start_date'])) {
                $query .= " AND DATE(q.quotation_date) >= :start_date";
                $params[':start_date'] = $filters['start_date'];
            }
            
            if (!empty($filters['end_date'])) {
                $query .= " AND DATE(q.quotation_date) <= :end_date";
                $params[':end_date'] = $filters['end_date'];
            }
            
            $query .= " GROUP BY q.id ORDER BY q.quotation_date DESC";
            
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="quotations_export_' . date('Y-m-d_His') . '.csv"');
            
            // Create output handle
            $output = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($output, [
                'Quotation Number',
                'Customer Name',
                'Contact Number',
                'Quotation Date',
                'Products',
                'Quantities',
                'Unit Prices',
                'Discounts',
                'Total Amount',
                'Tax',
                'Final Amount',
                'Status',
                'Validity Period (Days)',
                'Notes'
            ]);
            
            // Add data
            foreach ($quotations as $quotation) {
                fputcsv($output, [
                    $quotation['quotation_number'],
                    $quotation['customer_name'],
                    $quotation['contact_number'],
                    $quotation['quotation_date'],
                    $quotation['products'],
                    $quotation['quantities'],
                    $quotation['prices'],
                    $quotation['discounts'],
                    $quotation['total_amount'],
                    $quotation['tax'],
                    $quotation['final_amount'],
                    $quotation['status'],
                    $quotation['validity_period'],
                    $quotation['notes']
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

function generateQuotationNumber() {
    return 'QUO-' . date('Ymd') . '-' . substr(uniqid(), -5);
} 