<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

include 'public/db.php';
include 'public/header.php';

// Initialize cart in session if not set
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Add room to cart
if(isset($_POST['add_to_cart'])){
    $room_id = intval($_POST['room_id']);
    if(!in_array($room_id, $_SESSION['cart'])){
        $_SESSION['cart'][] = $room_id;
    }
}

// Remove room from cart
if(isset($_POST['remove'])){
    $room_id = intval($_POST['remove']);
    if(($key = array_search($room_id, $_SESSION['cart'])) !== false){
        unset($_SESSION['cart'][$key]);
    }
}

// Fetch rooms in cart
$rooms_in_cart = [];
if(!empty($_SESSION['cart'])){
    $ids = implode(',', $_SESSION['cart']);
    $stmt = $pdo->query("SELECT * FROM rooms WHERE id IN ($ids)");
    $rooms_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="cart">
    <h2>Your Cart</h2>

    <?php if($rooms_in_cart): ?>
        <table>
            <tr>
                <th>Room</th>
                <th>Price per Night</th>
                <th>Action</th>
            </tr>
            <?php foreach($rooms_in_cart as $room): ?>
            <tr>
                <td><?php echo htmlspecialchars($room['name']); ?></td>
                <td>$<?php echo htmlspecialchars($room['price']); ?></td>
                <td>
                    <form method="POST" action="cart.php">
                        <button type="submit" name="remove" value="<?php echo $room['id']; ?>">Remove</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><strong>Total: $<?php echo array_sum(array_column($rooms_in_cart, 'price')); ?></strong></p>
        <a href="booking.php" class="btn">Proceed to Booking</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="room.php" class="btn">Browse Rooms</a>
    <?php endif; ?>
</section>

<?php include 'public/footer.php'; ?>
