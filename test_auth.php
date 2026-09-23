<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$userModel = new Models\User();
$user = $userModel->authenticate('admin@company.com', 'admin123');

if ($user) {
    echo "Login successful!\n";
    print_r($user);
} else {
    echo "Login failed!\n";
    $dbUser = $userModel->findByEmail('admin@company.com');
    if ($dbUser) {
        echo "User found in DB. Hash: " . $dbUser['password'] . "\n";
        echo "Password verification: " . (password_verify('admin123', $dbUser['password']) ? 'true' : 'false') . "\n";
    } else {
        echo "User not found in DB.\n";
    }
}
