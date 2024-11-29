<?php
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama_fasilitas = htmlspecialchars($_POST['nama_fasilitas']);

    // Memasukkan data fasilitas ke dalam database
    $stmt = $pdo->prepare("INSERT INTO fasilitas (nama_fasilitas) VALUES (?)");
    $stmt->execute([$nama_fasilitas]);

    // Mengarahkan kembali dengan status success
    header("Location: ../fasilitas.php?status=success");
}
?>