<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

$stmt = $conn->prepare('SELECT id, username FROM users WHERE username = ? AND password = ?');
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    echo json_encode(['success' => true, 'message' => 'Login successful']);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}
?>
