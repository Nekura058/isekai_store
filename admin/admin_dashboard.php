<?php
// admin/admin_dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

$admin_id = $_SESSION['admin_id'];

$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = :id");
$stmt->execute([':id' => $admin_id]);
$adminData  = $stmt->fetch();
$lastLogin  = $adminData['last_login'];

$stmt = $pdo->prepare(
    "SELECT orders.*, users.username, users.phone_number, users.address
     FROM orders
     JOIN users ON orders.user_id = users.id
     WHERE orders.order_date > :last_login"
);
$stmt->execute([':last_login' => $lastLogin]);
$ordersSummary = $stmt->fetchAll();
$totalOrders   = count($ordersSummary);
$totalSales    = array_sum(array_column($ordersSummary, 'total'));

// Update last_login
$upd = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
$upd->execute([':id' => $admin_id]);

$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>

    <div class="admin-summary">
        <h3>Summary Since Last Login (<?php echo $lastLogin; ?>)</h3>
        <p>Total Orders: <?php echo $totalOrders; ?></p>
        <p>Total Sales: $<?php echo number_format($totalSales, 2); ?></p>
        <?php if ($totalOrders > 0) { ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Order ID</th><th>Buyer</th><th>Phone</th><th>Address</th>
                    <th>Total</th><th>Date</th><th>Status</th><th>Action</th>
                </tr>
                <?php foreach ($ordersSummary as $order) { ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                    <td>$<?php echo number_format($order['total'], 2); ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <?php if ($order['status'] !== 'Pending') { ?>
                            <a href="admin_delete_order.php?order_id=<?php echo $order['id']; ?>">Delete Order</a>
                        <?php } else { echo "N/A"; } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No new orders since your last login.</p>
        <?php } ?>
    </div>

    <div class="admin-actions">
        <button onclick="location.href='admin_add_product.php'">Add New Product</button>
        <button onclick="location.href='admin_delivery_check.php'">Delivery Check</button>
    </div>

    <h3>Inventory</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>ID</th><th>Category</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
        <?php foreach ($products as $product) {
            $catName = "Uncategorized";
            if ($product['category_id']) {
                $cs = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
                $cs->execute([':id' => $product['category_id']]);
                $cat = $cs->fetch();
                if ($cat) $catName = $cat['name'];
            }
        ?>
        <tr>
            <td><?php echo $product['id']; ?></td>
            <td><?php echo htmlspecialchars($catName); ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td>$<?php echo number_format($product['price'], 2); ?></td>
            <td><?php echo $product['stock']; ?></td>
            <td><a href="admin_edit_product.php?id=<?php echo $product['id']; ?>">Edit</a></td>
        </tr>
        <?php } ?>
    </table>

    <a href="../logout.php"><button>Logout</button></a>
    <script src="../script.js"></script>
</body>
</html>
