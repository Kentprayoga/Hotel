<?php
session_start();
include_once '../lib/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login terlebih dahulu.");
}

// Koneksi database
$database = new DatabaseConnection();
$pdo = $database->getConnection();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil ID Pesanan terbaru berdasarkan user
$query = "SELECT p.idpesan, p.nama, p.status FROM pemesanan p WHERE p.idtamu = :user_id ORDER BY p.idpesan DESC LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();

$pemesanan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pemesanan) {
    die("Data pemesanan tidak ditemukan.");
}

$idpesan = $pemesanan['idpesan'];
$nama = $pemesanan['nama'];
$status_sekarang = $pemesanan['status'];

if (isset($_POST['submit'])) {
    try {
        // Ambil data dari form
        $jumlah = $_POST['jumlah'];
        $bank = $_POST['bank'];
        $norek = $_POST['norek'];
        $namarek = $_POST['namarek'];
        $gambar = $_FILES['gambar'];

        // Validasi dan upload gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($gambar['type'], $allowedTypes)) {
            die("Hanya file gambar yang diizinkan.");
        }
        $randomFilename = time() . '-' . md5(rand()) . '-' . basename($gambar['name']);
        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/final_project/img/pembayaran/' . $randomFilename;

        if (!move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
            die("Gagal mengupload gambar.");
        }

        // Simpan data pembayaran
        $pdo->beginTransaction();
        $queryPembayaran = "INSERT INTO pembayaran (idpesan, nama, jumlah, bank, norek, namarek, gambar) 
                            VALUES (:idpesan, :nama, :jumlah, :bank, :norek, :namarek, :gambar)";
        $stmtPembayaran = $pdo->prepare($queryPembayaran);
        $stmtPembayaran->bindValue(':idpesan', $idpesan, PDO::PARAM_INT);
        $stmtPembayaran->bindValue(':nama', $nama, PDO::PARAM_STR);
        $stmtPembayaran->bindValue(':jumlah', $jumlah, PDO::PARAM_INT);
        $stmtPembayaran->bindValue(':bank', $bank, PDO::PARAM_STR);
        $stmtPembayaran->bindValue(':norek', $norek, PDO::PARAM_STR);
        $stmtPembayaran->bindValue(':namarek', $namarek, PDO::PARAM_STR);
        $stmtPembayaran->bindValue(':gambar', $randomFilename, PDO::PARAM_STR);
        $stmtPembayaran->execute();

        // Perbarui status pesanan menggunakan query manual
        $queryUpdateStatus = "UPDATE pemesanan SET status = 'Pending' WHERE idpesan = $idpesan";
        $result = $pdo->exec($queryUpdateStatus); // Menggunakan exec untuk langsung eksekusi query

        if ($result) {
            $pdo->commit();
            echo "<script>alert('Pembayaran berhasil dikirim. Status pesanan diperbarui.'); window.location.href='list_pesanan.php';</script>";
        } else {
            throw new Exception("Gagal memperbarui status pesanan.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran</title>
</head>

<body>
    <?php require('view/navbar.php'); ?>
    <h1>Form Pembayaran</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="idpesan">ID Pesanan:</label>
        <input type="text" name="idpesan" id="idpesan" value="<?php echo $idpesan; ?>" readonly><br>

        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" readonly><br>

        <label for="jumlah">Jumlah Pembayaran:</label>
        <input type="number" name="jumlah" id="jumlah" required><br>

        <label for="bank">Nama Bank:</label>
        <input type="text" name="bank" id="bank" required><br>

        <label for="norek">Nomor Rekening:</label>
        <input type="text" name="norek" id="norek" required><br>

        <label for="namarek">Nama Pemilik Rekening:</label>
        <input type="text" name="namarek" id="namarek" required><br>

        <label for="gambar">Bukti Pembayaran (Upload Foto):</label>
        <input type="file" name="gambar" id="gambar" required><br>

        <input type="submit" name="submit" value="Kirim Pembayaran">
    </form>
</body>

</html>