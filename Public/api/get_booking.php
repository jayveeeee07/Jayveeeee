<?php
require __DIR__ . '/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    http_response_code(401); echo json_encode(['error'=>'Not authenticated']); exit;
}

$user_id = $_SESSION['user_id'];
$room_id = intval($_POST['room_id'] ?? 0);
$check_in = $_POST['check_in'] ?? null;
$payment_method = $_POST['payment_method'] ?? '';
$reference_number = $_POST['reference_number'] ?? null;

$proofPath = null;
if (!empty($_FILES['proof']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION);
    $filename = 'proof_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (move_uploaded_file($_FILES['proof']['tmp_name'], $uploadDir . $filename)) {
        $proofPath = '/public/uploads/' . $filename;
    }
}

try {
    $ref = $reference_number ?: 'REF-' . strtoupper(bin2hex(random_bytes(4)));
    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, room_id, check_in, payment_method, proof_image, reference_number) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$user_id, $room_id, $check_in, $payment_method, $proofPath, $ref]);

    // create notification
    $bookingId = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare('INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)');
    $stmt2->execute([$user_id, 'Booking Submitted', 'Your booking is pending approval. Reference: ' . $ref]);

    echo json_encode(['success'=>true,'booking_id'=>$bookingId]);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
