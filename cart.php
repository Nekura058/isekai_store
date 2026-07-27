<?php
// cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare(
    "SELECT cart.*, products.name, products.price
     FROM cart
     JOIN products ON cart.product_id = products.id
     WHERE cart.user_id = :user_id"
);
$stmt->execute([':user_id' => $user_id]);
$rows  = $stmt->fetchAll();
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Cart</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Your Cart</h2>
    <?php if (count($rows) > 0) { ?>
        <form method="POST" action="update_cart.php">
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($rows as $row) {
                $subtotal = $row['price'] * $row['quantity'];
                $total   += $subtotal;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>
                        <input type="number" name="quantities[<?php echo $row['id']; ?>]"
                               value="<?php echo $row['quantity']; ?>" min="1">
                    </td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <button type="submit" name="update" value="<?php echo $row['id']; ?>">Update</button>
                        <a href="remove_from_cart.php?id=<?php echo $row['id']; ?>">Remove</a>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </table>
        </form>
        <br>
        <?php if ($total > 0) { ?>
            <a href="checkout.php"><button>Proceed to Checkout</button></a>
            <a href="index.php"><button>Want to add more?</button></a>
        <?php } ?>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>
    <script src="script.js"></script>
</body>
</html>
