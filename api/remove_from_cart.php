<?php
// remove_from_cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $cart_id, ':user_id' => $user_id]);
}
header("Location: cart.php");
exit();
?>
