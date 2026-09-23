<?php

namespace Config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $connection = $_ENV['DB_CONNECTION'] ?? 'mysql';
        
        try {
            if ($connection === 'sqlite') {
                $dbPath = dirname(__DIR__) . '/' . ($_ENV['DB_DATABASE'] ?? 'database/dashboard.db');
                $dsn = "sqlite:" . $dbPath;
                $this->pdo = new PDO($dsn);
                $this->pdo->exec("PRAGMA foreign_keys = ON;");
            } else {
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $db = $_ENV['DB_DATABASE'] ?? 'projectdashboard';
                $user = $_ENV['DB_USERNAME'] ?? 'ProjectDashboard';
                $pass = $_ENV['DB_PASSWORD'] ?? 'Satara@123';
                
                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
                $this->pdo = new PDO($dsn, $user, $pass);
            }
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
