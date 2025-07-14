<?php

// Include database configuration
require_once __DIR__ . '/db-config.php';

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
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public function closeConnection()
    {
        $this->conn = null;
    }
}
