<?php
include('../config/db.php');
include('../lib/Pemesanan.php');
include('../lib/User.php');
session_start();

// Set timezone ke Asia/Jakarta untuk tampilan waktu
date_default_timezone_set('Asia/Jakarta');

// Membuat objek User dan Pemesanan
$userObj = new User($pdo);
$pemesananObj = new Pemesanan($pdo);

// Pastikan pengguna sudah login
if ($userObj->isLoggedIn()) {
    $user_id = $userObj->getUserId();

    // Mendapatkan data pemesanan
    $pesanans = $pemesananObj->getPemesananByUserId($user_id);
} else {
    echo "<script>alert('Anda harus login terlebih dahulu.'); window.location.href = 'login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemesanan</title>
    <link rel="icon" href="../img/home/logo.jpg" type="image/png">
    <?php require('view/link.php'); ?>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .btn-konfirmasi {
        padding: 10px;
        background-color: #B40301;
        color: white;
        border: none;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <?php require('view/navbar.php'); ?>
    <div class="container mt-5">
        <h2>Kamar yang anda pesan</h2>

        <?php if (empty($pesanans)): ?>
        <p>Anda belum melakukan pemesanan kamar.</p>
        <?php else: ?>
        <div class="row">
            <?php foreach ($pesanans as $pesanan): ?>
            <?php
                    // Format tanggal dan waktu
                    $tglpesan = new DateTime($pesanan['tglpesan'], new DateTimeZone('Asia/Jakarta'));
                    $tglpesan_format = $tglpesan->format('Y-m-d H:i:s');

                    $batasbayar = new DateTime($pesanan['batasbayar'], new DateTimeZone('Asia/Jakarta'));
                    $batasbayar_format = $batasbayar->format('Y-m-d H:i:s');

                    $hargaa = number_format($pesanan['harga'], 0, ',', '.');
                    $totalbayarr = number_format($pesanan['totalbayar'], 0, ',', '.');
                    ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Kode Transaksi: <?php echo $pesanan['idpesan']; ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Tanggal Pemesanan:</strong> <?php echo $tglpesan_format; ?></p>
                        <p><strong>Tipe Kamar:</strong>
                            <?php echo isset($pesanan['tipe']) ? $pesanan['tipe'] : 'N/A'; ?></p>
                        <p><strong>Harga per Hari:</strong> Rp.
                            <?php echo isset($pesanan['harga']) ? $hargaa : 'N/A'; ?></p>
                        <p><strong>Jumlah Kamar:</strong> <?php echo $pesanan['jumlah']; ?></p>
                        <p><strong>Total Bayar:</strong> Rp. <?php echo $totalbayarr; ?></p>
                        <p><strong>Status:</strong> <?php echo $pesanan['status']; ?></p>
                        <p><strong>Check-In:</strong> <?php echo $pesanan['tglmasuk']; ?></p>
                        <p><strong>Check-Out:</strong> <?php echo $pesanan['tglkeluar']; ?></p>
                        <p><strong>Lama Menginap:</strong> <?php echo $pesanan['lamahari']; ?> Hari</p>
                        <p><strong>Batas Pembayaran:</strong> <?php echo $batasbayar_format; ?></p>
                        <div class="d-flex justify-content-between">
                            <?php 
    // Mendapatkan waktu sekarang
    $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    
    // Membandingkan waktu batas bayar dengan waktu sekarang
    $batasbayar_datetime = new DateTime($pesanan['batasbayar'], new DateTimeZone('Asia/Jakarta'));

    // Cek status pesanan dan waktu batas bayar
    $status = strtolower($pesanan['status']); // Ubah status menjadi huruf kecil

    // Jika status 'menunggu pembayaran' dan waktu sudah melewati batas bayar, tampilkan pesan
    if ($status === 'menunggu pembayaran' && $now > $batasbayar_datetime): 
        echo '<span class="text-danger">Batas waktu pembayaran telah terlewat</span>';
    else:
        // Jika status 'menunggu pembayaran' dan waktu belum melewati batas bayar, tampilkan tombol konfirmasi
        if ($status === 'menunggu pembayaran'): ?>
                            <a href="pembayaran.php?idpesan=<?php echo $pesanan['idpesan']; ?>"
                                class="btn btn-primary">Konfirmasi Pembayaran</a>
                            <?php endif; 
    endif;
    ?>

                            <?php 
    // Tombol batalkan pesanan hanya muncul jika statusnya 'menunggu pembayaran' dan batas waktu belum terlewat
    if ($status === 'menunggu pembayaran' && $now <= $batasbayar_datetime): ?>
                            <a href="../backend/batal_pesanan.php?id=<?php echo $pesanan['idpesan']; ?>"
                                class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batalkan
                                Pesanan</a>
                            <?php elseif ($status === 'pending'): ?>
                            <span class="text-warning">Pembayaran Pending</span>
                            <?php elseif ($status === 'check-in'): ?>
                            <span class="text-success">Sudah Check-In</span>
                            <?php elseif ($status === 'check-out'): ?>
                            <a href="review.php?id=<?php echo $pesanan['idpesan']; ?>" class="btn btn-warning">Berikan
                                Review</a>
                            <?php elseif ($status === 'berhasil'): ?>
                            <a href="cetak.php?id=<?php echo $pesanan['idpesan']; ?>" class="btn btn-info">Cetak</a>
                            <?php // Status dibatalkan tidak ditampilkan sama sekali ?>
                            <?php endif; ?>
                        </div>


                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php require('view/footer.php'); ?>
</body>

</html>