<?php
// config/db.php — creates a PDO handle in $pdo

$DB_HOST = 'localhost';
$DB_NAME = 'cinema_booking';
$DB_USER = 'root';
$DB_PASS = ''; // set if you use a password

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (Throwable $e) {
    // In production, avoid echoing $e->getMessage()
    http_response_code(500);
    exit('Database connection failed.');
}
