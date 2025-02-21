<?php
$host = 'localhost';
$dbname = 'estate';
$user = 'estate';
$pass = 'password';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS markers (
        photo VARCHAR(255) NOT NULL,
        x INTEGER NOT NULL,
        y INTEGER NOT NULL,
        name VARCHAR(50) NOT NULL,
        PRIMARY KEY (photo, x, y)
    )");
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
