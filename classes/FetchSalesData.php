<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Database.php';

class FetchSalesData
{
    private $db;
    public $msg;

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
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_sales'];
        }
        return 0;
    }

    /**
     * Fetch total purchase amount from purchases table
     */
    public function fetchTotalPurchases()
    {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) AS total_purchases FROM purchases WHERE status = 'Completed'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_purchases'];
        }
        return 0;
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
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_revenue'];
        }
        return 0;
    }

    /**
     * Fetch total customer orders count
     */
    public function fetchTotalOrders()
    {
        $sql = "SELECT COUNT(*) AS total_orders FROM customer_orders WHERE order_status != 'Cancelled'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_orders'];
        }
        return 0;
    }
}
