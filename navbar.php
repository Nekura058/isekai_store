<?php
// navbar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
    <div class="nav-left">
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="about.php">About Us</a>
    </div>
    <div class="nav-right">
        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="cart.php">Cart</a>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php">Logout</a>
        <?php } else { ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php } ?>
    </div>
</div>
<script src="script.js"></script>