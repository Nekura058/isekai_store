<?php
// update_cart.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities']) && is_array($_POST['quantities'])) {
    $stmt = $pdo->prepare("UPDATE cart SET quantity = :qty WHERE id = :id AND user_id = :user_id");
    foreach ($_POST['quantities'] as $cart_id => $new_quantity) {
        $new_quantity = intval($new_quantity);
        if ($new_quantity > 0) {
            $stmt->execute([':qty' => $new_quantity, ':id' => intval($cart_id), ':user_id' => $user_id]);
        }
    }
}
header("Location: cart.php");
exit();
?>
