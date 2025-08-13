<?php
header('Content-Type: application/json; charset=utf-8');
// Database connection using environment variables (Render or other host)
$DB_HOST = getenv('DB_HOST') !== false ? getenv('DB_HOST') : '127.0.0.1';
$DB_NAME = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'jayveeeee';
$DB_USER = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$DB_PORT = getenv('DB_PORT') !== false ? getenv('DB_PORT') : '3306';

try {
    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connect failed', 'detail' => $e->getMessage()]);
    exit;
}
