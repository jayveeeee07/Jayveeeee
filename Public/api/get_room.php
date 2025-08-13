<?php
require __DIR__ . '/db.php';
$stmt = $pdo->query('SELECT id, room_number, name, price, image, description FROM rooms ORDER BY id');
$rooms = $stmt->fetchAll();
echo json_encode(['rooms'=>$rooms]);
