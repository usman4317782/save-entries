<?php
require_once 'Database.php';

class AllStatistics
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Fetch the total number of brands
    public function getTotalBrands()
    {
        $query = "SELECT COUNT(*) as total FROM brands";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total number of categories
    public function getTotalCategories()
    {
        $query = "SELECT COUNT(*) as total FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total number of products
    public function getTotalProducts()
    {
        $query = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total number of customers
    public function getTotalCustomers()
    {
        $query = "SELECT COUNT(*) as total FROM customers";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total number of users (excluding admin and deleted users)
    public function getTotalUsers()
    {
        $query = "SELECT COUNT(*) as total FROM users WHERE username != 'admin' AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total number of vendors
    public function getTotalVendors()
    {
        $query = "SELECT COUNT(*) as total FROM vendors";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total number of sales
    public function getTotalSales()
    {
        $query = "SELECT COUNT(*) as total FROM sales";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }

    // Fetch the total revenue from sales
    public function getTotalRevenue()
    {
        $query = "SELECT SUM(final_amount) as total_revenue FROM sales";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (float) ($stmt->fetch()['total_revenue'] ?? 0);
    }

    // Fetch the total number of products per brand
    public function getProductsPerBrand()
    {
        $query = "SELECT b.brand_name, COUNT(p.id) as total_products 
                  FROM brands b 
                  LEFT JOIN products p ON b.id = p.brand_id 
                  GROUP BY b.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll() ?? [];
    }

    // Fetch the total number of products per category
    public function getProductsPerCategory()
    {
        $query = "SELECT c.category_name, COUNT(p.id) as total_products 
                  FROM categories c 
                  LEFT JOIN products p ON c.id = p.category_id 
                  GROUP BY c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll() ?? [];
    }

    // Fetch the total stock quantity of products
    public function getTotalStockQuantity()
    {
        $query = "SELECT SUM(stock_quantity) as total_stock FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total_stock'] ?? 0;
    }

    // Fetch the total value of products (price * stock_quantity)
    public function getTotalProductValue()
    {
        $query = "SELECT SUM(price * stock_quantity) as total_value FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (float) ($stmt->fetch()['total_value'] ?? 0);
    }

    // Fetch the average price of products
    public function getAverageProductPrice()
    {
        $query = "SELECT AVG(price) as average_price FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return (float) ($stmt->fetch()['average_price'] ?? 0);
    }

    // Fetch the number of active users (excluding admin and deleted users)
    public function getActiveUsers()
    {
        $query = "SELECT COUNT(*) as total_active FROM users WHERE is_active = 1 AND username != 'admin' AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total_active'] ?? 0;
    }

    // Fetch the number of verified users (excluding admin and deleted users)
    public function getVerifiedUsers()
    {
        $query = "SELECT COUNT(*) as total_verified FROM users WHERE is_verified = 1 AND username != 'admin' AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch()['total_verified'] ?? 0;
    }

    // Fetch sales trends (monthly)
    public function getSalesTrends()
    {
        $query = "SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, SUM(final_amount) as total_sales 
                  FROM sales 
                  GROUP BY DATE_FORMAT(sale_date, '%Y-%m') 
                  ORDER BY month";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll() ?? [];
    }
}
?>