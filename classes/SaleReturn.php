<?php
class SaleReturn {
    private $db;
    private $logger;

    public function __construct($db, $logger = null) {
        $this->db = $db;
        if ($logger === null) {
            // Initialize logger internally
            require_once __DIR__ . '/Logger.php';
            $this->logger = new Logger();
        } else {
            $this->logger = $logger;
        }
    }

    public function getAllReturns() {
        try {
            $query = "SELECT sr.id as return_id, sr.*, c.name as customer_name 
                     FROM sales_returns sr 
                     LEFT JOIN customers c ON sr.customer_id = c.customer_id 
                     ORDER BY sr.return_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ensure all values are properly encoded for JSON
            foreach ($results as &$row) {
                foreach ($row as $key => $value) {
                    if (is_numeric($value)) {
                        $row[$key] = floatval($value); // Convert numeric strings to actual numbers
                    } else if ($value === null) {
                        $row[$key] = ""; // Convert null to empty string
                    } else {
                        $row[$key] = strval($value); // Ensure strings are properly encoded
                    }
                }
            }
            
            return $results;
        } catch (PDOException $e) {
            $this->logger->error("Error fetching sales returns: " . $e->getMessage());
            return false;
        }
    }

    public function getReturnItems($returnId) {
        try {
            $query = "SELECT sri.*, p.product_name 
                     FROM sales_return_items sri 
                     LEFT JOIN products p ON sri.product_id = p.id 
                     WHERE sri.return_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$returnId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->error("Error fetching return items: " . $e->getMessage());
            return false;
        }
    }

    public function createReturn($data) {
        try {
            $this->db->beginTransaction();

            // Insert return
            $query = "INSERT INTO sales_returns (invoice_number, customer_id, return_date, 
                     total_amount, discount, tax, final_amount, payment_status, payment_method, 
                     notes, created_by) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['invoice_number'],
                $data['customer_id'],
                $data['return_date'],
                $data['total_amount'],
                $data['discount'],
                $data['tax'],
                $data['final_amount'],
                $data['payment_status'],
                $data['payment_method'],
                $data['notes'],
                $data['created_by']
            ]);

            $returnId = $this->db->lastInsertId();

            // Insert return items
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO sales_return_items (return_id, product_id, quantity, 
                         unit_price, discount, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $returnId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['total_price']
                ]);

                // Update product stock
                $query = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }

            $this->db->commit();
            return $returnId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->error("Error creating sales return: " . $e->getMessage());
            return false;
        }
    }

    public function getReturnById($returnId) {
        try {
            // Get return details
            $query = "SELECT sr.id as return_id, sr.*, c.name as customer_name, 
                     c.customer_id, c.contact_number as customer_phone, 
                     c.address as customer_address, sr.created_at
                     FROM sales_returns sr 
                     LEFT JOIN customers c ON sr.customer_id = c.customer_id 
                     WHERE sr.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$returnId]);
            $return = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$return) {
                return false;
            }

            // Get return items with product details
            $query = "SELECT sri.*, p.product_name, p.id as product_id 
                     FROM sales_return_items sri 
                     LEFT JOIN products p ON sri.product_id = p.id 
                     WHERE sri.return_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$returnId]);
            $return['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $return;
        } catch (PDOException $e) {
            $this->logger->error("Error fetching return: " . $e->getMessage());
            return false;
        }
    }

    public function deleteReturn($returnId) {
        try {
            $this->db->beginTransaction();

            // Get return items to update stock
            $query = "SELECT product_id, quantity FROM sales_return_items WHERE return_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$returnId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Update product stock
            foreach ($items as $item) {
                $query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }

            // Delete return items
            $query = "DELETE FROM sales_return_items WHERE return_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$returnId]);

            // Delete return
            $query = "DELETE FROM sales_returns WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$returnId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->error("Error deleting return: " . $e->getMessage());
            return false;
        }
    }

    public function bulkDeleteReturns($returnIds) {
        try {
            $this->db->beginTransaction();

            // Create placeholders for the IN clause
            $placeholders = str_repeat('?,', count($returnIds) - 1) . '?';

            // Get return items to update stock
            $query = "SELECT product_id, quantity FROM sales_return_items WHERE return_id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($returnIds);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Update product stock
            foreach ($items as $item) {
                $query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }

            // Delete return items
            $query = "DELETE FROM sales_return_items WHERE return_id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($returnIds);

            // Delete returns
            $query = "DELETE FROM sales_returns WHERE id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($returnIds);

            $this->db->commit();
            return ['status' => 'success', 'message' => 'Returns deleted successfully'];
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->error("Error in bulk delete returns: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Failed to delete returns'];
        }
    }

    public function updateReturn($returnId, $data) {
        try {
            $this->logger->error("Starting return update for ID: " . $returnId);
            $this->logger->error("Update data: " . print_r($data, true));
            
            // Validate required data
            if (!$returnId || !isset($data['customer_id']) || !isset($data['return_date']) || 
                !isset($data['items']) || !is_array($data['items']) || empty($data['items'])) {
                throw new Exception("Missing required data for update");
            }
            
            $this->db->beginTransaction();

            // Get current return items to revert stock changes
            $currentItems = $this->getReturnItems($returnId);
            if (!$currentItems) {
                throw new Exception("Failed to get current return items");
            }
            $this->logger->error("Current items: " . print_r($currentItems, true));

            // Revert previous stock changes
            foreach ($currentItems as $item) {
                try {
                    $query = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
                    $stmt = $this->db->prepare($query);
                    $result = $stmt->execute([$item['quantity'], $item['product_id']]);
                    
                    if (!$result) {
                        throw new Exception("Failed to revert stock for product ID: " . $item['product_id']);
                    }
                } catch (Exception $e) {
                    $this->logger->error("Error reverting stock: " . $e->getMessage());
                    throw $e;
                }
            }

            // Update return
            try {
                $query = "UPDATE sales_returns SET 
                         customer_id = ?, 
                         return_date = ?, 
                         total_amount = ?, 
                         tax = ?, 
                         final_amount = ?, 
                         payment_method = ?, 
                         notes = ? 
                         WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([
                    $data['customer_id'],
                    $data['return_date'],
                    $data['total_amount'],
                    $data['tax'],
                    $data['final_amount'],
                    $data['payment_method'],
                    $data['notes'] ?? null,
                    $returnId
                ]);
                
                if (!$result) {
                    throw new Exception("Failed to update sales_returns table");
                }
            } catch (Exception $e) {
                $this->logger->error("Error updating return: " . $e->getMessage());
                throw $e;
            }

            // Delete existing return items
            try {
                $query = "DELETE FROM sales_return_items WHERE return_id = ?";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$returnId]);
                
                if (!$result) {
                    throw new Exception("Failed to delete existing return items");
                }
            } catch (Exception $e) {
                $this->logger->error("Error deleting return items: " . $e->getMessage());
                throw $e;
            }

            // Insert updated return items and update stock
            foreach ($data['items'] as $item) {
                try {
                    // Validate item data
                    if (!isset($item['product_id'], $item['quantity'], $item['unit_price'], 
                             $item['discount'], $item['total_price'])) {
                        throw new Exception("Invalid item data structure");
                    }

                    // Insert return item
                    $query = "INSERT INTO sales_return_items (return_id, product_id, quantity, 
                             unit_price, discount, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $this->db->prepare($query);
                    $result = $stmt->execute([
                        $returnId,
                        $item['product_id'],
                        $item['quantity'],
                        $item['unit_price'],
                        $item['discount'],
                        $item['total_price']
                    ]);
                    
                    if (!$result) {
                        throw new Exception("Failed to insert return item for product ID: " . $item['product_id']);
                    }

                    // Update stock
                    $query = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
                    $stmt = $this->db->prepare($query);
                    $result = $stmt->execute([$item['quantity'], $item['product_id']]);
                    
                    if (!$result) {
                        throw new Exception("Failed to update stock for product ID: " . $item['product_id']);
                    }
                } catch (Exception $e) {
                    $this->logger->error("Error processing item: " . print_r($item, true));
                    $this->logger->error("Error message: " . $e->getMessage());
                    throw $e;
                }
            }

            $this->db->commit();
            $this->logger->error("Return update completed successfully");
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->logger->error("Error updating return: " . $e->getMessage());
            $this->logger->error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
} 