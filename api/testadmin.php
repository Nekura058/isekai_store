<?php
// Example: Create an admin (run this once and then remove it)
include 'config.php';
$username = 'admin';
$password = password_hash('adminpassword', PASSWORD_DEFAULT);
$conn->query("INSERT INTO admins (username, password) VALUES ('$username', '$password')");
?>
