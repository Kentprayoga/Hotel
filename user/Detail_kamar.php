<?php
session_start();
include '../config/db.php'; // Pastikan path db.php benar

// Pastikan ID kamar ada di URL
if (isset($_GET['idkamar'])) {
    $idkamar = $_GET['idkamar'];

    // Query untuk mengambil data kamar berdasarkan idkamar
    $sql = "SELECT k.idkamar, k.tipe, k.jumlah, k.harga, k.gambar, k.status, f.nama_fasilitas
            FROM kamar k
            LEFT JOIN kamar_fasilitas kf ON k.idkamar = kf.idkamar
            LEFT JOIN fasilitas f ON f.idfasilitas = kf.idfasilitas
            WHERE k.idkamar = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $idkamar, PDO::PARAM_INT);
    $stmt->execute();

    // Ambil data kamar yang dipilih
    $kamar = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kamar) {
        echo "<p>Kamar tidak ditemukan.</p>";
        exit;
    }

    // Ambil fasilitas kamar
    $fasilitas = [];
    $stmt_fasilitas = $pdo->prepare("SELECT f.nama_fasilitas FROM fasilitas f
                                     LEFT JOIN kamar_fasilitas kf ON f.idfasilitas = kf.idfasilitas
                                     WHERE kf.idkamar = ?");
    $stmt_fasilitas->bindParam(1, $idkamar, PDO::PARAM_INT);
    $stmt_fasilitas->execute();

    while ($row_fasilitas = $stmt_fasilitas->fetch(PDO::FETCH_ASSOC)) {
        $fasilitas[] = $row_fasilitas['nama_fasilitas'];
    }
} else {
    echo "<p>ID kamar tidak ditemukan.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kamar</title>
    <link rel="icon" href="../img/home/logo.jpg" type="image/png">
    <?php require('view/link.php'); ?>
    <link rel="stylesheet" href="css/kamar.css">
</head>

<body>

    <!-- Navbar -->
    <?php require('view/navbar.php'); ?>
    <!-- Navbar End -->

    <div class="container my-5">
        <!-- Header Section -->
        <h2 class="text-center"><?php echo htmlspecialchars($kamar['tipe']); ?></h2>
        <hr>

        <!-- Gambar Kamar -->
        <div class="row">
            <div class="col-md-6">
                <img src="../img/kamar/<?php echo htmlspecialchars($kamar['gambar']); ?>" class="img-fluid"
                    alt="Room Image">
            </div>

            <!-- Deskripsi Kamar -->
            <div class="col-md-6">
                <h4>Harga: IDR <?php echo number_format($kamar['harga'], 0, ',', '.'); ?> / malam</h4>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($kamar['status']); ?></p>
                <p><strong>Jumlah Kamar Tersedia:</strong> <?php echo htmlspecialchars($kamar['jumlah']); ?></p>

                <h5>Fasilitas Kamar:</h5>
                <ul>
                    <?php foreach ($fasilitas as $f): ?>
                    <li><?php echo htmlspecialchars($f); ?></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Tombol Pesan -->
                <a href="pesan.php?idkamar=<?php echo $kamar['idkamar']; ?>" class="btn btn-primary">Pesan</a>
            </div>

        </div>

        <!-- Footer -->
        <?php require('view/footer.php'); ?>
        <!-- Footer End -->

</body>

</html>