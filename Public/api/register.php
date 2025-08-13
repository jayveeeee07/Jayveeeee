<?php
// register.php
require __DIR__ . '/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error'=>'Method not allowed']);
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$fullname || !$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['error'=>'Missing required fields']);
    exit;
}

// Check duplicates
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error'=>'Username or email already exists']);
    exit;
}

// Handle photo upload (optional)
$photoPath = null;
if (!empty($_FILES['photo']['name'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $filename = 'user_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $uploadDir . $filename;
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        // store path relative to public
        $photoPath = '/public/uploads/' . $filename;
    }
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (fullname, username, email, password_hash, photo) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$fullname, $username, $email, $hash, $photoPath]);

echo json_encode(['success'=>true, 'message'=>'Account created']);
