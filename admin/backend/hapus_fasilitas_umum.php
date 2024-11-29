<?php
// Pastikan koneksi ke database sudah dilakukan
include '../config/db.php';

// Periksa apakah parameter 'idf_fasilitas' ada di URL
if (isset($_GET['idf_fasilitas'])) {
    $idfasilitas = $_GET['idf_fasilitas'];

    // Query untuk menghapus fasilitas umum berdasarkan ID
    $sql = "DELETE FROM fasilitas_umu WHERE idf_fasilitas = :idfasilitas";
    $stmt = $pdo->prepare($sql);
    
    // Ikat parameter dan eksekusi
    $stmt->bindParam(':idfasilitas', $idfasilitas, PDO::PARAM_INT);

    // Cek apakah penghapusan berhasil
    if ($stmt->execute()) {
        // Redirect ke halaman fasilitas umum setelah berhasil dihapus
        header("Location: ../fasilitas_umum.php?message=Fasilitas berhasil dihapus");
        exit();
    } else {
        // Jika gagal, beri pesan error
        echo "Gagal menghapus fasilitas umum.";
    }
} else {
    echo "ID fasilitas umum tidak ditemukan.";
}
?>