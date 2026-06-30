<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/session.php';

requireAuth();

$result = $conn->query("SELECT med_id, medicine_name, quantity, expiry_date FROM medicines");
$medicines = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$today = date('Y-m-d');
$total = count($medicines);
$safe = 0;
$expired = 0;
$expiring = 0;

foreach ($medicines as $medicine) {
    $expiry = $medicine['expiry_date'];
    if ($expiry < $today) {
        $expired++;
    } elseif (date_diff(date_create($today), date_create($expiry))->days <= 30) {
        $expiring++;
    } else {
        $safe++;
    }
}

echo json_encode([
    'success' => true,
    'data' => [
        'total' => $total,
        'safe' => $safe,
        'expired' => $expired,
        'expiring_soon' => $expiring
    ]
]);
?>
