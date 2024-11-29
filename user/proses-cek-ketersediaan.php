<?php
// Koneksi ke database
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan nilai dari form
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $idkamar = $_POST['tipe_kamar']; // ID kamar yang dipilih

    // Query untuk mengecek ketersediaan kamar berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM kamar WHERE idkamar = :idkamar");
    $stmt->bindParam(':idkamar', $idkamar, PDO::PARAM_INT);
    $stmt->execute();
    $kamar = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek jika kamar ditemukan dan tersedia
    if ($kamar && $kamar['status'] == 'Tersedia') {
        echo "<div class='alert alert-success'>Kamar " . $kamar['tipe'] . " tersedia untuk tanggal " . $checkin . " hingga " . $checkout . ".</div>";
    } else {
        echo "<div class='alert alert-danger'>Maaf, kamar yang Anda pilih tidak tersedia pada tanggal tersebut.</div>";
    }
}
?>