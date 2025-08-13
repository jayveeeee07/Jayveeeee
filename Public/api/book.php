<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Check if cart is empty
if(empty($_SESSION['cart'])){
    echo "<p>Your cart is empty. <a href='room.php'>Browse Rooms</a></p>";
    include 'public/footer.php';
    exit;
}

// Fetch rooms in cart
$ids = implode(',', $_SESSION['cart']);
$stmt = $pdo->query("SELECT * FROM rooms WHERE id IN ($ids)");
$rooms_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$success = '';

if(isset($_POST['book'])){
    $user_id = $_SESSION['user_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $total_price = array_sum(array_column($rooms_in_cart, 'price'));

    if(!$checkin || !$checkout){
        $errors[] = "Please select check-in and check-out dates.";
    } else {
        // Save booking
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_ids, checkin, checkout, total_price) VALUES (?, ?, ?, ?, ?)");
        $room_ids_str = implode(',', array_column($rooms_in_cart, 'id'));
        $stmt->execute([$user_id, $room_ids_str, $checkin, $checkout, $total_price]);

        // Clear cart
        $_SESSION['cart'] = [];
        $success = "Booking successful! Your rooms are reserved.";
    }
}
?>

<section class="booking">
    <h2>Booking / Reservation</h2>

    <?php if($errors): ?>
        <div class="errors">
            <?php foreach($errors as $err) echo "<p>$err</p>"; ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success">
            <p><?php echo $success; ?></p>
            <a href="home.php" class="btn">Go to Home</a>
        </div>
    <?php else: ?>
        <h3>Rooms in your cart:</h3>
        <ul>
            <?php foreach($rooms_in_cart as $room): ?>
                <li><?php echo htmlspecialchars($room['name'])." - $".$room['price']; ?></li>
            <?php endforeach; ?>
        </ul>
        <form method="POST" action="booking.php">
            <label>Check-in Date:</label>
            <input type="date" name="checkin" required>
            <label>Check-out Date:</label>
            <input type="date" name="checkout" required>
            <p><strong>Total Price: $<?php echo array_sum(array_column($rooms_in_cart, 'price')); ?></strong></p>
            <button type="submit" name="book">Confirm Booking</button>
        </form>
    <?php endif; ?>
</section>

<?php include 'public/footer.php'; ?>
