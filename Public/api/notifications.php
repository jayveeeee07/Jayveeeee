<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Fetch user notifications
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="notifications">
    <h2>Your Notifications</h2>

    <?php if(empty($notifications)): ?>
        <p>No notifications at the moment.</p>
    <?php else: ?>
        <ul class="notification-list">
            <?php foreach($notifications as $note): ?>
                <li>
                    <strong><?php echo htmlspecialchars($note['title']); ?></strong> 
                    <span class="date"><?php echo date("d M Y H:i", strtotime($note['created_at'])); ?></span>
                    <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php include 'public/footer.php'; ?>
