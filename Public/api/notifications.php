<?php
require __DIR__ . '/db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) { http_response_code(401); echo json_encode(['error'=>'Not authenticated']); exit; }
$stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? OR user_id IS NULL ORDER BY created_at DESC');
$stmt->execute([$user_id]);
echo json_encode(['notifications'=>$stmt->fetchAll()]);
