<?php
require __DIR__ . '/db.php';
session_start();

$role = $_SESSION['role'] ?? 'guest';
$user_id = $_SESSION['user_id'] ?? null;

try {
    if ($role === 'admin') {
        $stmt = $pdo->query('SELECT b.*, u.fullname, r.room_number, r.name as room_name FROM bookings b LEFT JOIN users u ON b.user_id=u.id LEFT JOIN rooms r ON b.room_id=r.id ORDER BY b.created_at DESC');
        echo json_encode(['bookings'=>$stmt->fetchAll()]);
        exit;
    }
    if ($user_id) {
        $stmt = $pdo->prepare('SELECT b.*, r.room_number, r.name as room_name FROM bookings b LEFT JOIN rooms r ON b.room_id=r.id WHERE b.user_id=? ORDER BY b.created_at DESC');
        $stmt->execute([$user_id]);
        echo json_encode(['bookings'=>$stmt->fetchAll()]);
        exit;
    }
    http_response_code(401);
    echo json_encode(['error'=>'Not authorized']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
