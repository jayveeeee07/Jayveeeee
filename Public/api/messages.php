<?php
require __DIR__ . '/db.php';
session_start();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $user_id = $_SESSION['user_id'] ?? null;
        $subject = $_POST['subject'] ?? '';
        $body = $_POST['body'] ?? '';
        $stmt = $pdo->prepare('INSERT INTO messages (user_id, subject, body) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $subject, $body]);
        echo json_encode(['success'=>true]); exit;
    }
    if ($method === 'GET') {
        if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $stmt = $pdo->query('SELECT m.*, u.fullname FROM messages m LEFT JOIN users u ON m.user_id=u.id ORDER BY created_at DESC');
            echo json_encode(['messages'=>$stmt->fetchAll()]); exit;
        }
        $user_id = $_SESSION['user_id'] ?? null;
        $stmt = $pdo->prepare('SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$user_id]);
        echo json_encode(['messages'=>$stmt->fetchAll()]); exit;
    }
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['error'=>$e->getMessage()]);
}
