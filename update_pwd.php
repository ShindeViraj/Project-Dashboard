<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$pdo = Config\Database::getInstance();
$hash = password_hash('Satara@123', PASSWORD_BCRYPT);
$stmt = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$stmt->execute([$hash, 'admin@company.com']);
echo "Admin password updated to Satara@123\n";
