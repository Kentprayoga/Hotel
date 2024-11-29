<?php
include 'config/db.php';

if (isset($_GET['idfasilitas'])) {
    $idfasilitas = $_GET['idfasilitas'];

    // Ambil data fasilitas untuk mengetahui gambar yang akan dihapus
    $sql = "SELECT gambar FROM fasilitas_umu WHERE idf_fasilitas = :idfasilitas";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idfasilitas', $idfasilitas);
    $stmt->execute();
    $fasilitas = $stmt->fetch(PDO::FETCH_ASSOC);

    // Hapus gambar jika ada
    if ($fasilitas && file_exists("img/fasilitas/" . $fasilitas['gambar'])) {
        unlink("../img/fasilitas/" . $fasilitas['gambar']);
    }

    // Hapus data fasilitas dari database
    $delete_sql = "DELETE FROM fasilitas_umu WHERE idf_fasilitas = :idfasilitas";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->bindParam(':idfasilitas', $idfasilitas);
    $stmt->execute();

    echo "<script>alert('Fasilitas berhasil dihapus!'); window.location.href='list_fasilitas_umum.php';</script>";
} else {
    echo "<script>alert('ID fasilitas tidak ditemukan!'); window.location.href='list_fasilitas_umum.php';</script>";
}
?>