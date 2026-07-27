<?php
// admin/admin_delivery_check.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

$stmt = $pdo->query(
    "SELECT orders.*, users.username
     FROM orders
     JOIN users ON orders.user_id = users.id
     WHERE orders.status = 'Pending'"
);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Delivery Check</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Delivery Check</h2>
    <a href="admin_dashboard.php"><button>Back to Dashboard</button></a>
    <?php if (count($orders) > 0) { ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Order ID</th><th>Buyer</th><th>Total</th><th>Order Date</th><th>Status</th><th>Action</th>
            </tr>
            <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td>$<?php echo number_format($order['total'], 2); ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo $order['status']; ?></td>
                <td>
                    <form method="POST" action="admin_update_delivery.php">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <button type="submit" name="mark_delivered">Mark as Delivered</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No orders pending delivery check.</p>
    <?php } ?>
    <script src="../script.js"></script>
</body>
</html>
