<?php
// checkout.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT cart.*, products.price, products.stock
     FROM cart
     JOIN products ON cart.product_id = products.id
     WHERE cart.user_id = :user_id"
);
$stmt->execute([':user_id' => $user_id]);
$items = $stmt->fetchAll();

if (empty($items)) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

$total = 0;
foreach ($items as $row) {
    $total += $row['price'] * $row['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($total <= 0) throw new Exception("Cart is empty.");

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (:user_id, :total)");
        $stmt->execute([':user_id' => $user_id, ':total' => $total]);
        $order_id = $pdo->lastInsertId();

        $insItem  = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :qty, :price)");
        $updStock = $pdo->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id");

        foreach ($items as $item) {
            $insItem->execute([
                ':order_id'   => $order_id,
                ':product_id' => $item['product_id'],
                ':qty'        => $item['quantity'],
                ':price'      => $item['price'],
            ]);
            $updStock->execute([':qty' => $item['quantity'], ':id' => $item['product_id']]);
        }

        $del = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $del->execute([':user_id' => $user_id]);

        $pdo->commit();
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Order Success</title>
    <link rel='stylesheet' type='text/css' href='styles.css'>
</head>
<body>
    <header><h1>Order Placed Successfully!</h1></header>
    <div class='message-container'>
        <p>Your order has been successfully placed.</p>
        <p>Thank you for shopping with us!</p>
    </div>
    <footer><p>Redirecting you to the homepage...</p></footer>
    <script>setTimeout(function(){ window.location.href = 'index.php'; }, 3000);</script>
</body>
</html>";
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p>Failed to place order: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Checkout</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Checkout</h2>
    <p>Total Amount: $<?php echo number_format($total, 2); ?></p>
    <?php if ($total > 0) { ?>
        <form method="POST" action="checkout.php">
            <button type="submit">Place Order</button>
        </form>
    <?php } else { ?>
        <p>Your cart is empty.</p>
    <?php } ?>
</body>
</html>
