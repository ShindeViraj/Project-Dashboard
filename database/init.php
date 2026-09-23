<?php

$dbPath = __DIR__ . '/dashboard.db';

// Remove existing DB to start fresh
if (file_exists($dbPath)) {
    unlink($dbPath);
}

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Enable foreign keys
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // Read and execute schema
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schema);
    echo "Schema created successfully.\n";

    // Read and execute seed
    $seed = file_get_contents(__DIR__ . '/seed.sql');
    $pdo->exec($seed);
    echo "Database seeded successfully.\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
