<?php
include 'public/db.php';
include 'public/header.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
    if($stmt->execute([$username, $email, $password])) {
        $message = "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        $message = "Registration failed. Try again.";
    }
}
?>

<section class="register">
    <h2>Register</h2>
    <?php if($message) echo "<p>$message</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</section>

<?php include 'public/footer.php'; ?>
