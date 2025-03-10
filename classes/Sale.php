<?php
class Sale {
    private $db;
    private $logger;

    public function __construct($db, $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function getAllSales() {
        try {
            $query = "SELECT s.id as sale_id, s.*, c.name as customer_name 
                     FROM sales s 
                     LEFT JOIN customers c ON s.customer_id = c.customer_id 
                     ORDER BY s.sale_date DESC";
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
            $this->logger->logError("Error fetching sales: " . $e->getMessage());
            return false;
        }
    }

    public function getSaleItems($saleId) {
        try {
            $query = "SELECT si.*, p.name as product_name 
                     FROM sale_items si 
                     LEFT JOIN products p ON si.product_id = p.id 
                     WHERE si.sale_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->logError("Error fetching sale items: " . $e->getMessage());
            return false;
        }
    }

    public function getPayments($saleId) {
        try {
            $query = "SELECT * FROM payments WHERE sale_id = ? ORDER BY payment_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->logError("Error fetching payments: " . $e->getMessage());
            return false;
        }
    }

    public function createSale($data) {
        try {
            $this->db->beginTransaction();

            // Insert sale
            $query = "INSERT INTO sales (invoice_number, customer_id, sale_date, total_amount, 
                     discount, tax, final_amount, payment_status, payment_method, notes, created_by) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['invoice_number'],
                $data['customer_id'],
                $data['sale_date'],
                $data['total_amount'],
                $data['discount'],
                $data['tax'],
                $data['final_amount'],
                $data['payment_status'],
                $data['payment_method'],
                $data['notes'],
                $data['created_by']
            ]);

            $saleId = $this->db->lastInsertId();

            // Insert sale items
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, 
                         discount, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $saleId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['total_price']
                ]);
            }

            // If there's an initial payment
            if (!empty($data['payment'])) {
                $query = "INSERT INTO payments (sale_id, amount, payment_method, payment_date, 
                         transaction_id, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $saleId,
                    $data['payment']['amount'],
                    $data['payment']['payment_method'],
                    $data['payment']['payment_date'],
                    $data['payment']['transaction_id'],
                    $data['payment']['notes'],
                    $data['created_by']
                ]);
            }

            $this->db->commit();
            return $saleId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error creating sale: " . $e->getMessage());
            return false;
        }
    }

    public function addPayment($data) {
        try {
            $this->db->beginTransaction();

            // Insert payment
            $query = "INSERT INTO payments (sale_id, amount, payment_method, payment_date, 
                     transaction_id, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['sale_id'],
                $data['amount'],
                $data['payment_method'],
                $data['payment_date'],
                $data['transaction_id'],
                $data['notes'],
                $data['created_by']
            ]);

            // Update sale payment status
            $this->updateSalePaymentStatus($data['sale_id']);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error adding payment: " . $e->getMessage());
            return false;
        }
    }

    private function updateSalePaymentStatus($saleId) {
        try {
            // Get total amount and paid amount
            $query = "SELECT final_amount FROM sales WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            $sale = $stmt->fetch(PDO::FETCH_ASSOC);

            $query = "SELECT SUM(amount) as paid_amount FROM payments WHERE sale_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            // Determine payment status
            $status = 'pending';
            if ($payment['paid_amount'] >= $sale['final_amount']) {
                $status = 'paid';
            } elseif ($payment['paid_amount'] > 0) {
                $status = 'partially_paid';
            }

            // Update status
            $query = "UPDATE sales SET payment_status = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$status, $saleId]);

            return true;
        } catch (PDOException $e) {
            $this->logger->logError("Error updating sale payment status: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSale($saleId) {
        try {
            $this->db->beginTransaction();

            // Delete payments
            $query = "DELETE FROM payments WHERE sale_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);

            // Delete sale items
            $query = "DELETE FROM sale_items WHERE sale_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);

            // Delete sale
            $query = "DELETE FROM sales WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error deleting sale: " . $e->getMessage());
            return false;
        }
    }

    public function updateSale($saleId, $data) {
        try {
            $this->db->beginTransaction();

            // Update sale
            $query = "UPDATE sales SET 
                     customer_id = ?, 
                     sale_date = ?, 
                     total_amount = ?, 
                     discount = ?, 
                     tax = ?, 
                     final_amount = ?, 
                     payment_method = ?, 
                     notes = ? 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['customer_id'],
                $data['sale_date'],
                $data['total_amount'],
                $data['discount'],
                $data['tax'],
                $data['final_amount'],
                $data['payment_method'],
                $data['notes'],
                $saleId
            ]);

            // Delete existing sale items
            $query = "DELETE FROM sale_items WHERE sale_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);

            // Insert updated sale items
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, 
                         discount, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $saleId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['total_price']
                ]);
            }

            // Add new payment if provided
            if (!empty($data['payment'])) {
                $query = "INSERT INTO payments (sale_id, amount, payment_method, payment_date, 
                         transaction_id, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $saleId,
                    $data['payment']['amount'],
                    $data['payment']['payment_method'],
                    $data['payment']['payment_date'],
                    $data['payment']['transaction_id'],
                    $data['payment']['notes'],
                    $data['payment']['created_by']
                ]);

                // Update sale payment status
                $this->updateSalePaymentStatus($saleId);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error updating sale: " . $e->getMessage());
            return false;
        }
    }

    public function getSaleById($saleId) {
        try {
            // Get sale details
            $query = "SELECT s.id as sale_id, s.*, c.name as customer_name, c.customer_id, 
                     c.contact_number as customer_phone, c.address as customer_address,
                     s.created_at
                     FROM sales s 
                     LEFT JOIN customers c ON s.customer_id = c.customer_id 
                     WHERE s.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            $sale = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$sale) {
                return false;
            }

            // Get sale items with product details
            $query = "SELECT si.*, p.product_name, p.id as product_id 
                     FROM sale_items si 
                     LEFT JOIN products p ON si.product_id = p.id 
                     WHERE si.sale_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            $sale['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get payments
            $query = "SELECT * FROM payments WHERE sale_id = ? ORDER BY payment_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$saleId]);
            $sale['payments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $sale;
        } catch (PDOException $e) {
            $this->logger->logError("Error fetching sale: " . $e->getMessage());
            return false;
        }
    }

    public function bulkDeleteSales($saleIds) {
        try {
            $this->db->beginTransaction();

            // Create placeholders for the IN clause
            $placeholders = str_repeat('?,', count($saleIds) - 1) . '?';

            // Delete payments
            $query = "DELETE FROM payments WHERE sale_id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($saleIds);

            // Delete sale items
            $query = "DELETE FROM sale_items WHERE sale_id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($saleIds);

            // Delete sales
            $query = "DELETE FROM sales WHERE id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($saleIds);

            $this->db->commit();
            return ['status' => 'success', 'message' => 'Sales deleted successfully'];
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error in bulk delete sales: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Failed to delete sales'];
        }
    }
}
