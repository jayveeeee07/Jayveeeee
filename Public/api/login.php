<?php
// login.php
require __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit;
}

$identifier = trim($_POST['identifier'] ?? '');
$password = $_POST['password'] ?? '';

if (!$identifier || !$password) {
    http_response_code(400); echo json_encode(['error'=>'Missing fields']); exit;
}

$stmt = $pdo->prepare('SELECT id, fullname, username, email, password_hash, role FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401); echo json_encode(['error'=>'Invalid credentials']); exit;
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'] ?? 'user';
unset($user['password_hash']);
echo json_encode(['success'=>true,'user'=>$user]);
