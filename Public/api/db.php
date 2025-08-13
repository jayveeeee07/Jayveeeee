<?php
$host = "dpg-d2e2pdur433s73cvb8tg-a";
$dbname = "jayveeeee";
$user = "jayveeeee_user";
$pass = "f6IEwMp1oiGbjP6UuGWfUzzbUQkiD4ax";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
