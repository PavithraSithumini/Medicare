<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'medicare';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}
?>
