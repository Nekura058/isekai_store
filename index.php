<?php
session_start();
include 'config.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to isekai store</h1>
        <img src="designer.png" alt="Isekai Store Logo" class="logo" />
    </header>
    <?php include 'navbar.php'; ?>
    <h2>Latest Products</h2>
    <div class="product-list">
        <?php foreach ($products as $product) { ?>
            <div class="product-item">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>"
                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                     width="200" height="200">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                <p>Stock: <?php echo $product['stock']; ?></p>
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <label for="quantity_<?php echo $product['id']; ?>">Quantity:</label>
                    <input type="number" name="quantity" id="quantity_<?php echo $product['id']; ?>" value="1" min="1"><br>
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php } ?>
    </div>
    <script src="script.js"></script>
</body>
</html>
