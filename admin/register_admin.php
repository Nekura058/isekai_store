<?php
// admin/register_admin.php
// Run this once to seed an admin account, then delete or restrict access to this file.
include '../config.php';

$username      = 'fido';       // change as needed
$passwordPlain = 'alyosha';    // change as needed

$hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
$stmt->execute([':username' => $username, ':password' => $hashedPassword]);
echo "Admin registered successfully.";
?>
