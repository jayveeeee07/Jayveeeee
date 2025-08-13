<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Fetch rooms from the database
$stmt = $pdo->query("SELECT * FROM rooms ORDER BY id ASC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="rooms">
    <h2>Available Rooms</h2>
    <div class="room-list">
        <?php if($rooms): ?>
            <?php foreach($rooms as $room): ?>
                <div class="room-card">
                    <img src="public/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                    <p><?php echo htmlspecialchars($room['description']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($room['price']); ?> / night</p>
                    <form method="POST" action="cart.php">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No rooms available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'public/footer.php'; ?>
