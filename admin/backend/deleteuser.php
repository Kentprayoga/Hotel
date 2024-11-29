<?php
// Perbaiki path menuju file db.php
include "../config/db.php";

// Mengambil ID dari URL
$ambil = $_GET['id'];

// Query untuk menghapus user berdasarkan ID
$sql = $pdo->query("DELETE FROM users WHERE user_id = '$ambil'");

// Mengalihkan kembali ke halaman data pengguna setelah penghapusan
echo "<script>document.location.href='../readuser.php';</script>";
?>