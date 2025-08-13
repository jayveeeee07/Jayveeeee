<?php
require __DIR__ . '/db.php';
session_start();

if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); echo json_encode(['error'=>'Not admin']); exit;
}

$action = $_POST['action'] ?? '';
$booking_id = intval($_POST['booking_id'] ?? 0);
if (!$booking_id || !in_array($action, ['approve','reject'])) {
    http_response_code(400); echo json_encode(['error'=>'Bad request']); exit;
}

try {
    if ($action === 'approve') {
        $pin = strtoupper(bin2hex(random_bytes(3)));
        $stmt = $pdo->prepare("UPDATE bookings SET status='Approved', access_pin=? WHERE id=?");
        $stmt->execute([$pin, $booking_id]);

        // notify user
        $stmt2 = $pdo->prepare("SELECT user_id FROM bookings WHERE id=?");
        $stmt2->execute([$booking_id]);
        $u = $stmt2->fetch();
        if ($u) {
            $stmt3 = $pdo->prepare('INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)');
            $stmt3->execute([$u['user_id'], 'Booking Approved', 'Your booking was approved. PIN: ' . $pin]);
        }
        echo json_encode(['success'=>true,'pin'=>$pin]); exit;
    }
    if ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE bookings SET status='Rejected' WHERE id=?");
        $stmt->execute([$booking_id]);
        echo json_encode(['success'=>true]); exit;
    }
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
