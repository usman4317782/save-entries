<?php
class Quotation {
    private $db;
    private $logger;

    public function __construct($db, $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function getAllQuotations() {
        try {
            $query = "SELECT q.id as quotation_id, q.*, c.name as customer_name 
                     FROM quotations q 
                     LEFT JOIN customers c ON q.customer_id = c.customer_id 
                     ORDER BY q.quotation_date DESC";
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
            $this->logger->logError("Error fetching quotations: " . $e->getMessage());
            return false;
        }
    }

    public function getQuotationItems($quotationId) {
        try {
            $query = "SELECT qi.*, p.product_name 
                     FROM quotation_items qi 
                     LEFT JOIN products p ON qi.product_id = p.id 
                     WHERE qi.quotation_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logger->logError("Error fetching quotation items: " . $e->getMessage());
            return false;
        }
    }

    public function createQuotation($data) {
        try {
            $this->db->beginTransaction();

            // Insert quotation
            $query = "INSERT INTO quotations (quotation_number, customer_id, quotation_date, total_amount, 
                     discount, tax, final_amount, status, validity_period, notes, created_by) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['quotation_number'],
                $data['customer_id'],
                $data['quotation_date'],
                $data['total_amount'],
                $data['discount'],
                $data['tax'],
                $data['final_amount'],
                $data['status'],
                $data['validity_period'],
                $data['notes'],
                $data['created_by']
            ]);

            $quotationId = $this->db->lastInsertId();

            // Insert quotation items
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO quotation_items (quotation_id, product_id, quantity, unit_price, 
                         discount, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $quotationId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['total_price']
                ]);
            }

            $this->db->commit();
            return $quotationId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error creating quotation: " . $e->getMessage());
            return false;
        }
    }

    public function deleteQuotation($quotationId) {
        try {
            $this->db->beginTransaction();

            // Delete quotation items
            $query = "DELETE FROM quotation_items WHERE quotation_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);

            // Delete quotation
            $query = "DELETE FROM quotations WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error deleting quotation: " . $e->getMessage());
            return false;
        }
    }

    public function updateQuotation($quotationId, $data) {
        try {
            $this->db->beginTransaction();

            // Update quotation
            $query = "UPDATE quotations SET 
                     customer_id = ?, 
                     quotation_date = ?, 
                     total_amount = ?, 
                     discount = ?, 
                     tax = ?, 
                     final_amount = ?, 
                     status = ?, 
                     validity_period = ?,
                     notes = ? 
                     WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['customer_id'],
                $data['quotation_date'],
                $data['total_amount'],
                $data['discount'],
                $data['tax'],
                $data['final_amount'],
                $data['status'],
                $data['validity_period'],
                $data['notes'],
                $quotationId
            ]);

            // Delete existing quotation items
            $query = "DELETE FROM quotation_items WHERE quotation_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);

            // Insert updated quotation items
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO quotation_items (quotation_id, product_id, quantity, unit_price, 
                         discount, total_price) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $quotationId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['discount'],
                    $item['total_price']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error updating quotation: " . $e->getMessage());
            return false;
        }
    }

    public function getQuotationById($quotationId) {
        try {
            // Get quotation details
            $query = "SELECT q.id as quotation_id, q.*, c.name as customer_name, c.customer_id, 
                     c.contact_number as customer_phone, c.address as customer_address,
                     q.created_at
                     FROM quotations q 
                     LEFT JOIN customers c ON q.customer_id = c.customer_id 
                     WHERE q.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);
            $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$quotation) {
                return false;
            }

            // Get quotation items with product details
            $query = "SELECT qi.*, p.product_name, p.id as product_id 
                     FROM quotation_items qi 
                     LEFT JOIN products p ON qi.product_id = p.id 
                     WHERE qi.quotation_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);
            $quotation['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $quotation;
        } catch (PDOException $e) {
            $this->logger->logError("Error fetching quotation: " . $e->getMessage());
            return false;
        }
    }

    public function bulkDeleteQuotations($quotationIds) {
        try {
            $this->db->beginTransaction();

            // Create placeholders for the IN clause
            $placeholders = str_repeat('?,', count($quotationIds) - 1) . '?';

            // Delete quotation items
            $query = "DELETE FROM quotation_items WHERE quotation_id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($quotationIds);

            // Delete quotations
            $query = "DELETE FROM quotations WHERE id IN ($placeholders)";
            $stmt = $this->db->prepare($query);
            $stmt->execute($quotationIds);

            $this->db->commit();
            return ['status' => 'success', 'message' => 'Quotations deleted successfully'];
        } catch (PDOException $e) {
            $this->db->rollBack();
            $this->logger->logError("Error in bulk delete quotations: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Failed to delete quotations'];
        }
    }
    
    public function convertToSale($quotationId) {
        try {
            $this->db->beginTransaction();
            
            // Get quotation details
            $quotation = $this->getQuotationById($quotationId);
            if (!$quotation) {
                throw new Exception("Quotation not found");
            }
            
            // Create sale data
            require_once 'Sale.php';
            $sale = new Sale($this->db, $this->logger);
            
            $saleData = [
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_id' => $quotation['customer_id'],
                'sale_date' => date('Y-m-d'),
                'total_amount' => $quotation['total_amount'],
                'discount' => $quotation['discount'],
                'tax' => $quotation['tax'],
                'final_amount' => $quotation['final_amount'],
                'payment_status' => 'pending',
                'payment_method' => null,
                'notes' => "Converted from Quotation #" . $quotation['quotation_number'] . "\n" . $quotation['notes'],
                'created_by' => $quotation['created_by'],
                'items' => []
            ];
            
            // Add items
            foreach ($quotation['items'] as $item) {
                $saleData['items'][] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'],
                    'total_price' => $item['total_price']
                ];
            }
            
            // Create the sale
            $saleId = $sale->createSale($saleData);
            if (!$saleId) {
                throw new Exception("Failed to create sale");
            }
            
            // Update quotation status to converted
            $query = "UPDATE quotations SET status = 'converted' WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$quotationId]);
            
            $this->db->commit();
            return $saleId;
        } catch (Exception $e) {
            $this->db->rollBack();
            $this->logger->logError("Error converting quotation to sale: " . $e->getMessage());
            return false;
        }
    }
    
    private function generateInvoiceNumber() {
        return 'INV-' . date('Ymd') . '-' . substr(uniqid(), -5);
    }
} 