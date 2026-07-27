<?php
// config.php
// Set your Supabase/Railway PostgreSQL connection string here
// Format: host=... port=5432 dbname=... user=... password=...
$dsn      = getenv('DB_DSN')      ?: 'pgsql:host=localhost;port=5432;dbname=isekai_db2';
$db_user  = getenv('DB_USER')     ?: 'postgres';
$db_pass  = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
