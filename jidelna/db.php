<?php
$host = '127.0.0.1';
$dbname = 'objednavkovy_system';
$user = 'root';
$pass = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}
?>