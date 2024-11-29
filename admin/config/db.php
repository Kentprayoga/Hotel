<?php
$host = 'localhost'; // Host database
$dbname = 'grand_kenari'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>