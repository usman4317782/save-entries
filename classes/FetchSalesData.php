<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchSalesData
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Fetch total sales amount from sales table
     */
    public function fetchTotalSales()
    {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM sales WHERE status = 'Completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_sales'] ?? 0;
    }

    /**
     * Fetch total purchase amount from purchases table
     */
    public function fetchTotalPurchases()
    {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) AS total_purchases FROM purchases WHERE status = 'Completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_purchases'] ?? 0;
    }

    /**
     * Fetch total revenue (Sales - Purchases)
     */
    public function fetchTotalRevenue()
    {
        $sql = "
            SELECT 
                COALESCE((SELECT SUM(total_amount) FROM sales WHERE status = 'Completed'), 0) 
                - 
                COALESCE((SELECT SUM(total_amount) FROM purchases WHERE status = 'Completed'), 0) 
                AS total_revenue
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_revenue'] ?? 0;
    }

    /**
     * Fetch total customer orders count
     */
    public function fetchTotalOrders()
    {
        $sql = "SELECT COUNT(*) AS total_orders FROM customer_orders WHERE order_status != 'Cancelled'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_orders'] ?? 0;
    }

    /**
     * Fetch total products count
     */
    public function fetchTotalProducts()
    {
        $sql = "SELECT COUNT(*) AS total_products FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_products'] ?? 0;
    }

    /**
     * Fetch total customers count
     */
    public function fetchTotalCustomers()
    {
        $sql = "SELECT COUNT(*) AS total_customers FROM customers";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_customers'] ?? 0;
    }

    /**
     * Fetch total suppliers count
     */
    public function fetchTotalSuppliers()
    {
        $sql = "SELECT COUNT(*) AS total_suppliers FROM suppliers";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_suppliers'] ?? 0;
    }

    /**
     * Fetch total categories count
     */
    public function fetchTotalCategories()
    {
        $sql = "SELECT COUNT(*) AS total_categories FROM categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_categories'] ?? 0;
    }

    /**
     * Fetch total stock quantity
     */
    public function fetchTotalStock()
    {
        $sql = "SELECT COALESCE(SUM(quantity), 0) AS total_stock FROM stock WHERE transaction_type = 'In'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_stock'] ?? 0;
    }
}

?>
