<?php
// register.php
include 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname       = $_POST['fullname'];
    $username_input = $_POST['username'];
    $phone_number   = $_POST['phone_number'];
    $address        = $_POST['address'];
    $email          = $_POST['email'];
    $password_input = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
    $stmt->execute([':username' => $username_input, ':email' => $email]);

    if ($stmt->fetch()) {
        $errors[] = "Username or Email already registered.";
    } else {
        $hashedPassword = password_hash($password_input, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "INSERT INTO users (fullname, username, phone_number, address, email, password)
             VALUES (:fullname, :username, :phone, :address, :email, :password)"
        );
        $stmt->execute([
            ':fullname' => $fullname,
            ':username' => $username_input,
            ':phone'    => $phone_number,
            ':address'  => $address,
            ':email'    => $email,
            ':password' => $hashedPassword,
        ]);
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Isekai - Register</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Register</h2>
    <?php foreach ($errors as $error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="register.php" onsubmit="return validatePassword()">
        <label>Full Name</label>
        <input type="text" name="fullname" required>
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Phone number</label>
        <input type="text" name="phone_number" required>
        <label>Address</label>
        <input type="text" name="address" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" id="password" required>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required>
        <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password
        <p id="password-error" style="color:red;display:none;">Passwords do not match.</p>
        <button type="submit">Register</button>
    </form>
    <script>
        function togglePasswordVisibility() {
            var p = document.getElementById("password");
            var c = document.getElementById("confirm_password");
            p.type = p.type === "password" ? "text" : "password";
            c.type = c.type === "password" ? "text" : "password";
        }
        function validatePassword() {
            var match = document.getElementById("password").value === document.getElementById("confirm_password").value;
            document.getElementById("password-error").style.display = match ? "none" : "block";
            return match;
        }
    </script>
</body>
</html>
