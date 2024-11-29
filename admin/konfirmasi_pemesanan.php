<?php
// Koneksi ke database
include('../config/db.php');

// Ambil ID pesanan dari URL
$idpesan = $_GET['id'];

// Pastikan ID valid
if (isset($idpesan) && is_numeric($idpesan)) {
    // Query untuk mengupdate status pesanan menjadi 'Pending'
    $sql = "UPDATE pemesanan SET status = 'Berhasil' WHERE idpesan = :idpesan";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect ke halaman utama setelah berhasil mengupdate
        header("Location: List_pembayaran.php?status=konfirmasi-success");
        exit();
    } else {
        // Redirect ke halaman utama dengan pesan error
        header("Location: List_pembayaran.php?status=konfirmasi-failed");
        exit();
    }
} else {
    // Redirect jika ID tidak valid
    header("Location: List_pembayaran.php?status=invalid-id");
    exit();
}
?>