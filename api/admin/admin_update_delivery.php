<?php
// admin/admin_update_delivery.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $stmt = $pdo->prepare("UPDATE orders SET status = 'Delivered' WHERE id = :id");
    $stmt->execute([':id' => $order_id]);
}
header("Location: admin_delivery_check.php");
exit();
?>
