<?php
// admin/admin_delete_order.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = :id AND status != 'Pending'");
    $stmt->execute([':id' => $order_id]);
}
header("Location: admin_dashboard.php");
exit();
?>
