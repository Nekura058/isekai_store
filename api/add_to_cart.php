<?php
// add_to_cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $user_id    = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id]);

    if ($stmt->fetch()) {
        $upd = $pdo->prepare("UPDATE cart SET quantity = quantity + :qty WHERE user_id = :user_id AND product_id = :product_id");
        $upd->execute([':qty' => $quantity, ':user_id' => $user_id, ':product_id' => $product_id]);
    } else {
        $ins = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :qty)");
        $ins->execute([':user_id' => $user_id, ':product_id' => $product_id, ':qty' => $quantity]);
    }
    header("Location: cart.php");
    exit();
}
?>
