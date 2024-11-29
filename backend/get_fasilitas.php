<?php
include('config/db.php'); // Menghubungkan dengan file config.php

// Query untuk mengambil semua data fasilitas
$sql = "SELECT * FROM fasilitas_umu";
$stmt = $pdo->query($sql);

// Menyimpan hasil query dalam array
$fasilitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah ada data
if ($fasilitas) {
    // Data berhasil diambil
} else {
    echo "Tidak ada data fasilitas.";
}
?>