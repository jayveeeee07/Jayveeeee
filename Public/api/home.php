<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Example: Fetch latest news/promos from database
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
$news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="home">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <div class="promos">
        <h3>Latest Promotions</h3>
        <?php if($news_items): ?>
            <ul>
                <?php foreach($news_items as $news): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($news['title']); ?></strong><br>
                        <?php echo htmlspecialchars($news['content']); ?><br>
                        <em><?php echo date("d M Y", strtotime($news['created_at'])); ?></em>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No promotions available right now.</p>
        <?php endif; ?>
    </div>

    <div class="quick-links">
        <a href="room.php" class="btn">Rooms</a>
        <a href="cart.php" class="btn">Cart</a>
        <a href="booking.php" class="btn">Book Now</a>
    </div>
</section>

<?php include 'public/footer.php'; ?>
