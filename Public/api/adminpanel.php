<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Handle user deletion
if(isset($_GET['delete_user'])){
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['delete_user']]);
    $success = "User deleted successfully.";
}

// Fetch all users
$users = $pdo->query("SELECT id, name, email, phone, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all bookings
$bookings = $pdo->query("SELECT b.id, u.name as user_name, r.name as room_name, b.check_in, b.check_out 
                         FROM bookings b
                         JOIN users u ON b.user_id = u.id
                         JOIN rooms r ON b.room_id = r.id
                         ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch notifications
$notifications = $pdo->query("SELECT * FROM notifications ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="admin-panel">
    <h2>Admin Panel</h2>

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

    <h3>Users</h3>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Created At</th><th>Action</th></tr>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['name']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['phone']); ?></td>
                <td><?= $user['created_at']; ?></td>
                <td>
                    <a href="adminpanel.php?delete_user=<?= $user['id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Bookings</h3>
    <table>
        <tr><th>ID</th><th>User</th><th>Room</th><th>Check-in</th><th>Check-out</th></tr>
        <?php foreach($bookings as $b): ?>
            <tr>
                <td><?= $b['id']; ?></td>
                <td><?= htmlspecialchars($b['user_name']); ?></td>
                <td><?= htmlspecialchars($b['room_name']); ?></td>
                <td><?= $b['check_in']; ?></td>
                <td><?= $b['check_out']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Notifications</h3>
    <table>
        <tr><th>ID</th><th>Title</th><th>Message</th><th>Created At</th></tr>
        <?php foreach($notifications as $n): ?>
            <tr>
                <td><?= $n['id']; ?></td>
                <td><?= htmlspecialchars($n['title']); ?></td>
                <td><?= htmlspecialchars($n['message']); ?></td>
                <td><?= $n['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<?php include 'public/footer.php'; ?>
