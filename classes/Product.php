<?php
require_once 'Database.php';

class Product
{
    private $conn;
    private $table = "products";

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Check for duplicate product
    public function isDuplicate($category_id, $brand_id, $product_name, $id = null)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE product_name = :product_name";
        if ($id) {
            $query .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':product_name', $product_name, PDO::PARAM_STR);
        if ($id) {
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }

    // Fetch all products with filtering options
    public function getProducts($search = '', $category_id = '', $brand_id = '', $minPrice = '', $maxPrice = '', $startDate = '', $endDate = '', $stock_status = '') {
        $query = "SELECT p.*, 
                  COALESCE(c.category_name, 'N/A') as category_name, 
                  COALESCE(b.brand_name, 'N/A') as brand_name 
                  FROM {$this->table} p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  LEFT JOIN brands b ON p.brand_id = b.id 
                  WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND (p.product_name LIKE :search_name OR p.description LIKE :search_desc)";
        }
        if (!empty($category_id)) {
            $query .= " AND p.category_id = :category_id";
        }
        if (!empty($brand_id)) {
            $query .= " AND p.brand_id = :brand_id";
        }
        if (!empty($minPrice)) {
            $query .= " AND p.price >= :minPrice";
        }
        if (!empty($maxPrice)) {
            $query .= " AND p.price <= :maxPrice";
        }
        if (!empty($startDate)) {
            $query .= " AND p.created_at >= :startDate";
        }
        if (!empty($endDate)) {
            $query .= " AND p.created_at <= :endDate";
        }
        if (!empty($stock_status)) {
            $query .= " AND p.stock_status = :stock_status";
        }
        $query .= " ORDER BY p.created_at DESC";
    
        $stmt = $this->conn->prepare($query);
    
        if (!empty($search)) {
            $stmt->bindValue(':search_name', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
        }
        if (!empty($category_id)) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }
        if (!empty($brand_id)) {
            $stmt->bindValue(':brand_id', $brand_id, PDO::PARAM_INT);
        }
        if (!empty($minPrice)) {
            $stmt->bindValue(':minPrice', $minPrice, PDO::PARAM_STR);
        }
        if (!empty($maxPrice)) {
            $stmt->bindValue(':maxPrice', $maxPrice, PDO::PARAM_STR);
        }
        if (!empty($startDate)) {
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        }
        if (!empty($endDate)) {
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        }
        if (!empty($stock_status)) {
            $stmt->bindValue(':stock_status', $stock_status, PDO::PARAM_STR);
        }
    
        try {
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the data for display
            foreach ($products as &$product) {
                $product['price'] = $product['price'] ? number_format($product['price'], 2) : 'N/A';
                $product['stock_quantity'] = $product['stock_quantity'] !== null ? $product['stock_quantity'] : 'N/A';
                $product['sku'] = $product['sku'] ?: 'N/A';
            }
            
            return $products;
        } catch (PDOException $e) {
            file_put_contents('pdo_error.txt', $e->getMessage() . "\nQuery: " . $query, FILE_APPEND);
            throw $e;
        }
    }

    // Fetch brands
    public function getBrands()
    {
        $query = "SELECT * FROM brands ORDER BY brand_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch categories
    public function getCategories()
    {
        $query = "SELECT * FROM categories ORDER BY category_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single product by ID
    public function getProductById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create product
    public function createProduct($category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status)
    {
        if ($this->isDuplicate($category_id, $brand_id, $product_name)) {
            return false;
        }

        $query = "INSERT INTO {$this->table} (category_id, brand_id, product_name, description, price, stock_quantity, sku, stock_status) 
                  VALUES (:category_id, :brand_id, :product_name, :description, :price, :stock_quantity, :sku, :stock_status)";
        $stmt = $this->conn->prepare($query);

        // Convert empty strings to null for optional fields
        $category_id = $category_id === '' ? null : $category_id;
        $brand_id = $brand_id === '' ? null : $brand_id;
        $price = $price === '' ? null : $price;
        $stock_quantity = $stock_quantity === '' ? null : $stock_quantity;
        $description = $description === '' ? null : $description;
        $sku = $sku === '' ? null : $sku;
        $stock_status = $stock_status === '' ? 'Non Stock' : $stock_status;

        return $stmt->execute([
            ':category_id' => $category_id,
            ':brand_id' => $brand_id,
            ':product_name' => $product_name,
            ':description' => $description,
            ':price' => $price,
            ':stock_quantity' => $stock_quantity,
            ':sku' => $sku,
            ':stock_status' => $stock_status
        ]);
    }

    // Update product
    public function updateProduct($id, $category_id, $brand_id, $product_name, $description, $price, $stock_quantity, $sku, $stock_status)
    {
        if ($this->isDuplicate($category_id, $brand_id, $product_name, $id)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET 
                  category_id = :category_id,
                  brand_id = :brand_id,
                  product_name = :product_name,
                  description = :description,
                  price = :price,
                  stock_quantity = :stock_quantity,
                  sku = :sku,
                  stock_status = :stock_status
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Convert empty strings to null for optional fields
        $category_id = $category_id === '' ? null : $category_id;
        $brand_id = $brand_id === '' ? null : $brand_id;
        $price = $price === '' ? null : $price;
        $stock_quantity = $stock_quantity === '' ? null : $stock_quantity;
        $description = $description === '' ? null : $description;
        $sku = $sku === '' ? null : $sku;
        $stock_status = $stock_status === '' ? 'Non Stock' : $stock_status;

        return $stmt->execute([
            ':id' => $id,
            ':category_id' => $category_id,
            ':brand_id' => $brand_id,
            ':product_name' => $product_name,
            ':description' => $description,
            ':price' => $price,
            ':stock_quantity' => $stock_quantity,
            ':sku' => $sku,
            ':stock_status' => $stock_status
        ]);
    }

    // Delete single product
    public function deleteProduct($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    // Bulk delete products
    public function bulkDelete($ids)
    {
        $query = "DELETE FROM {$this->table} WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")";
        return $this->conn->exec($query);
    }
}
