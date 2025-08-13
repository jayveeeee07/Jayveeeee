<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Fetch user messages
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="messages">
    <h2>Your Messages</h2>

    <?php if(empty($messages)): ?>
        <p>No messages yet.</p>
    <?php else: ?>
        <ul class="message-list">
            <?php foreach($messages as $msg): ?>
                <li>
                    <strong><?php echo htmlspecialchars($msg['title']); ?></strong> 
                    <span class="date"><?php echo date("d M Y H:i", strtotime($msg['created_at'])); ?></span>
                    <p><?php echo nl2br(htmlspecialchars($msg['content'])); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php include 'public/footer.php'; ?>
