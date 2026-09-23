<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=projectdashboard', 'ProjectDashboard', 'Satara@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schema);
    echo "Schema created.\n";
    
    $seed = file_get_contents(__DIR__ . '/seed.sql');
    $pdo->exec($seed);
    echo "Seeded.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
