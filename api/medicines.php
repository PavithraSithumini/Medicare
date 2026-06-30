<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/session.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT med_id, medicine_name, quantity, expiry_date FROM medicines ORDER BY expiry_date ASC");
    $medicines = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    echo json_encode([
        'success' => true,
        'data' => $medicines
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['medicine_name'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $expiry = trim($_POST['expiry_date'] ?? '');

    if ($name === '' || $quantity <= 0 || $expiry === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid medicine data']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO medicines (medicine_name, quantity, expiry_date) VALUES (?, ?, ?)");
    $stmt->bind_param('sis', $name, $quantity, $expiry);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Medicine added successfully'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $putData);
    $id = (int)($putData['med_id'] ?? 0);
    $name = trim($putData['medicine_name'] ?? '');
    $quantity = (int)($putData['quantity'] ?? 0);
    $expiry = trim($putData['expiry_date'] ?? '');

    if ($id <= 0 || $name === '' || $quantity <= 0 || $expiry === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid update data']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE medicines SET medicine_name = ?, quantity = ?, expiry_date = ? WHERE med_id = ?");
    $stmt->bind_param('sisi', $name, $quantity, $expiry, $id);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Medicine updated successfully'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $deleteData);
    $id = (int)($deleteData['med_id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid medicine id']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM medicines WHERE med_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Medicine deleted successfully'
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Method not supported']);
?>
