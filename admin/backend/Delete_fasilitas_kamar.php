<?php
// Pastikan koneksi ke database sudah dilakukan
include '../config/db.php';

// Periksa apakah parameter 'idfasilitas' ada di URL
if (isset($_GET['idfasilitas'])) {
    $idfasilitas = $_GET['idfasilitas'];

    // Query untuk menghapus fasilitas berdasarkan ID
    $sql = "DELETE FROM fasilitas WHERE idfasilitas = :idfasilitas";
    $stmt = $pdo->prepare($sql);
    
    // Ikat parameter dan eksekusi
    $stmt->bindParam(':idfasilitas', $idfasilitas, PDO::PARAM_INT);

    // Cek apakah penghapusan berhasil
    if ($stmt->execute()) {
        // Redirect ke halaman fasilitas setelah berhasil dihapus
        header("Location: ../fasilitas.php?message=Fasilitas berhasil dihapus");
        exit();
    } else {
        // Jika gagal, beri pesan error
        echo "Gagal menghapus fasilitas.";
    }
} else {
    echo "ID fasilitas tidak ditemukan.";
}
?>