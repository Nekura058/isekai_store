<?php
// admin/admin_add_product.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = floatval($_POST['price']);
    $stock       = intval($_POST['stock']);
    $category_id = (isset($_POST['category_id']) && $_POST['category_id'] !== '') ? intval($_POST['category_id']) : null;

    $image = 'default.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename   = basename($_FILES['image']['name']);
        $targetFile = "../images/" . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $filename;
        } else {
            $errors[] = "Failed to upload image.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO products (category_id, name, description, price, stock, image)
             VALUES (:cat, :name, :desc, :price, :stock, :image)"
        );
        $stmt->execute([':cat' => $category_id, ':name' => $name, ':desc' => $description,
                        ':price' => $price, ':stock' => $stock, ':image' => $image]);
        header("Location: admin_dashboard.php");
        exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai Admin - Add Product</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
    <h2>Add New Product</h2>
    <?php foreach ($errors as $error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="admin_add_product.php" enctype="multipart/form-data">
        <label>Category:</label>
        <select name="category_id">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat) { ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php } ?>
        </select>
        <label>Product Name:</label>
        <textarea name="name" required rows="2" cols="20"></textarea>
        <label>Description:</label>
        <textarea name="description" required rows="5" cols="20"></textarea>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>
        <label>Stock:</label>
        <input type="number" name="stock" value="0" required>
        <label>Image:</label>
        <input type="file" name="image">
        <button type="submit">Add Product</button>
    </form>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
