<?php
include "./config/db.php";
include "./config/Kamar.php"; // Pastikan class Kamar sudah diletakkan di file ini

// Membuat objek Kamar
$kamar = new Kamar($pdo);

// Ambil ID kamar yang ingin dihapus
$idkamar = $_GET['idkamar'] ?? null;
if (!$idkamar) {
    echo "<script>alert('ID kamar tidak ditemukan!'); document.location.href='../kamar.php';</script>";
    exit;
}

// Ambil data kamar yang akan dihapus
$dataKamar = $kamar->getKamarById($idkamar);
if (!$dataKamar) {
    echo "<script>alert('Kamar tidak ditemukan!'); document.location.href='../kamar.php';</script>";
    exit;
}

try {
    // Hapus fasilitas yang terkait dengan kamar
    $kamar->deleteFasilitas($idkamar);

    // Hapus gambar dari server
    if ($dataKamar['gambar']) {
        $kamar->deleteGambar($dataKamar['gambar']);
    }

    // Hapus kamar dari tabel kamar
    $kamar->deleteKamar($idkamar);

    echo "<script>alert('Data Kamar Berhasil Dihapus'); document.location.href='list_kamar.php';</script>";
} catch (Exception $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
}