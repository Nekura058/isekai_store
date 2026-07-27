<?php
// admin/admin_edit_product.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';
$errors = [];

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$product_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if (!$product) die("Product not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        if (!empty($product['image'])) {
            $imagePath = "../images/" . $product['image'];
            if (file_exists($imagePath)) unlink($imagePath);
        }
        $pdo->prepare("DELETE FROM products WHERE id = :id")->execute([':id' => $product_id]);
        header("Location: admin_dashboard.php");
        exit();
    }

    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = floatval($_POST['price']);
    $stock       = intval($_POST['stock']);
    $category_id = (isset($_POST['category_id']) && $_POST['category_id'] !== '') ? intval($_POST['category_id']) : null;
    $image       = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename   = basename($_FILES['image']['name']);
        $targetFile = "../images/" . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $filename;
        } else {
            $errors[] = "Failed to upload new image.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "UPDATE products SET category_id=:cat, name=:name, description=:desc,
             price=:price, stock=:stock, image=:image WHERE id=:id"
        );
        $stmt->execute([':cat' => $category_id, ':name' => $name, ':desc' => $description,
                        ':price' => $price, ':stock' => $stock, ':image' => $image, ':id' => $product_id]);
        header("Location: admin_dashboard.php");
        exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Edit Product</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Edit Product</h2>
    <?php foreach ($errors as $error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="admin_edit_product.php?id=<?php echo $product_id; ?>" enctype="multipart/form-data">
        <label>Category:</label>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat) { ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $product['category_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php } ?>
        </select><br>
        <label>Product Name:</label>
        <textarea name="name" required rows="2" cols="20"><?php echo htmlspecialchars($product['name']); ?></textarea>
        <label>Description:</label>
        <textarea name="description" required rows="5" cols="20"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
        <label>Current Image:</label>
        <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" width="80" height="80">
        <label>Change Image (optional):</label>
        <input type="file" name="image">
        <button type="submit">Update Product</button>
    </form>
    <form method="POST" action="admin_edit_product.php?id=<?php echo $product_id; ?>"
          onsubmit="return confirm('Are you sure you want to delete this product?');">
        <button type="submit" name="delete">Delete Product</button>
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
