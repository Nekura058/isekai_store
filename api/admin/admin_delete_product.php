<?php
// admin/admin_delete_product.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $pdo->prepare("DELETE FROM products WHERE id = :id")->execute([':id' => $product_id]);
}
header("Location: admin_dashboard.php");
exit();
?>
