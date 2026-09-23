<?php
namespace Core;

use PDO;
use PDOException;

abstract class Model {
    protected $db;
    protected $table = '';

    public function __construct() {
        try {
            $connection = $_ENV['DB_CONNECTION'] ?? 'sqlite';
            if ($connection === 'sqlite') {
                $dbPath = __DIR__ . '/../' . ($_ENV['DB_DATABASE'] ?? 'database/dashboard.db');
                $dsn = "sqlite:" . $dbPath;
                $this->db = new PDO($dsn);
            } else {
                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                $port = $_ENV['DB_PORT'] ?? '3306';
                $dbName = $_ENV['DB_DATABASE'] ?? 'forge';
                $user = $_ENV['DB_USERNAME'] ?? 'root';
                $pass = $_ENV['DB_PASSWORD'] ?? '';
                $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
                $this->db = new PDO($dsn, $user, $pass);
            }
            
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function find($id) {
        $stmt = $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public function findAll() {
        $stmt = $this->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $this->query($sql, array_values($data));
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = "";
        foreach (array_keys($data) as $key) {
            $fields .= "$key = ?, ";
        }
        $fields = rtrim($fields, ', ');
        
        $sql = "UPDATE {$this->table} SET $fields WHERE id = ?";
        $params = array_values($data);
        $params[] = $id;
        
        return $this->query($sql, $params);
    }

    public function delete($id) {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}
