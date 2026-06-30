<?php
$host = 'localhost';
$dbname = 'medicare';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_errno) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed',
        'error' => $conn->connect_error
    ]);
    exit;
}

$conn->set_charset('utf8mb4');
?>
