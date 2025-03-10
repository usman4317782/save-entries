<?php
include __DIR__ . '/../config/config.php';

class Database
{
    private $host = DB_HOST;
    private $db_name = DB_DATABASE;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $conn;

    public function __construct()
    {
        try {
            // Database connection logic using PDO
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Log the error automatically using the logger if database connection fails
            if (isset($GLOBALS['logger'])) {
                $GLOBALS['logger']->error('Database connection failed: ' . $e->getMessage());
            }

            // Display a user-friendly error message without exposing sensitive information
            die('Unable to connect to the database. Please try again later.');
        }
    }

    // Getter method to access the database connection
    public function getConnection()
    {
        // Ensure the connection is valid before returning it
        if ($this->conn === null) {
            throw new Exception("Database connection is not established.");
        }
        return $this->conn;
    }
}
