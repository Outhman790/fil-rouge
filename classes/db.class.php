<?php

// Include database configuration
require_once __DIR__ . '/../config/db-config.php';

class DB
{
    protected $conn;
    protected $host;
    protected $dbname;
    protected $username;
    protected $password;

    public function __construct()
    {
        global $dbConfig;
        $this->host = $dbConfig['host'];
        $this->dbname = $dbConfig['dbname'];
        $this->username = $dbConfig['username'];
        $this->password = $dbConfig['password'];
    }


    public function connect()
    {
        // Reuse existing connection if available
        if ($this->conn !== null) {
            return $this->conn;
        }
        
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Enable query caching for better performance
            $this->conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            return $this->conn;
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Connection failed. Please try again later.");
        }
    }


    public function closeConnection()
    {
        $this->conn = null;
    }
}
