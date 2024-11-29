<?php
// Koneksi ke database
include('../config/db.php');
if (isset($_GET['status'])) {
    $status = $_GET['status'];

    if ($status == 'konfirmasi-success') {
        echo '<div class="alert alert-success">Konfirmasi berhasil!</div>';
    } elseif ($status == 'konfirmasi-failed') {
        echo '<div class="alert alert-danger">Konfirmasi gagal!</div>';
    } elseif ($status == 'batalkan-success') {
        echo '<div class="alert alert-success">Pesanan dibatalkan!</div>';
    } elseif ($status == 'batalkan-failed') {
        echo '<div class="alert alert-danger">Pembatalan gagal!</div>';
    } elseif ($status == 'invalid-id') {
        echo '<div class="alert alert-warning">ID tidak valid!</div>';
    }
}

// Query untuk mengambil data pembayaran dan status pemesanan
$sql = "SELECT p.idpembayaran, p.idpesan, p.jumlah, p.bank, p.norek, p.namarek, p.gambar, pe.tglpesan, pe.status
        FROM pembayaran p
        JOIN pemesanan pe ON p.idpesan = pe.idpesan
        ORDER BY p.idpembayaran DESC"; // Menampilkan data berdasarkan ID Pembayaran terbaru
$stmt = $pdo->query($sql);

// Mengambil hasil query dan menyimpannya dalam array
$pembayaranData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran Admin</title>

    <!-- Link Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .h-font {
        font-family: 'Cardo', serif;
    }

    /* Membuat navbar tetap berada di atas saat di-scroll */
    .container-fluid.bg-dark.text-light {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
        padding: 10px 20px;
        background-color: #343a40;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Memberikan jarak tambahan pada body agar konten tidak tertutup oleh navbar */
    body {
        padding-top: 70px;
    }

    /* Menu samping/dashboard tetap di posisi kiri */
    #dashboard-menu {
        position: fixed;
        top: 70px;
        left: 0;
        height: 100%;
        width: 200px;
        z-index: 998;
        overflow-y: auto;
    }

    /* Responsif untuk perangkat dengan layar lebih kecil dari 992px */
    @media screen and (max-width: 992px) {
        .container-fluid.bg-dark.text-light {
            position: relative;
            padding: 10px 15px;
        }

        #dashboard-menu {
            position: relative;
            height: auto;
            width: 100%;
            top: 0;
            display: none;
        }

        #main-content {
            margin-left: 0;
        }

        .navbar-toggler {
            display: block;
        }

        .navbar-collapse {
            width: 100%;
            display: none;
        }

        .navbar-collapse.show {
            display: block;
        }

        .navbar-nav {
            flex-direction: column;
        }

        .nav-item {
            text-align: center;
        }

        .nav-link {
            padding: 10px;
        }
    }
    </style>
</head>

<body>
    <!-- Include Header -->
    <?php include "view/header.php"; ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto">
                <div class="form-container">
                    <div class="form-input">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="text-center">Data Pembayaran</h2>

                                <!-- Tabel Menampilkan Data Pembayaran -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID Pesanan</th>
                                            <th>Jumlah Pembayaran</th>
                                            <th>Bank</th>
                                            <th>Nomor Rekening</th>
                                            <th>Nama Rekening</th>
                                            <th>Gambar Bukti Pembayaran</th>
                                            <th>Tanggal Pesanan</th>
                                            <th>Status</th> <!-- Tambahkan kolom Status -->
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Gunakan foreach untuk menampilkan data
                                        $no = 1;
                                        foreach ($pembayaranData as $row):
                                        ?>
                                        <tr>
                                            <td><?php echo $row['idpesan']; ?></td>
                                            <td>Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                                            <td><?php echo $row['bank']; ?></td>
                                            <td><?php echo $row['norek']; ?></td>
                                            <td><?php echo $row['namarek']; ?></td>
                                            <td>
                                                <a href="../img/upload/<?php echo $row['gambar']; ?>" target="_blank">
                                                    Lihat Bukti Pembayaran
                                                </a>
                                            </td>
                                            <td><?php echo date('d-m-Y', strtotime($row['tglpesan'])); ?></td>
                                            <td><?php echo $row['status']; ?></td> <!-- Menampilkan status -->
                                            <td>
                                                <a href="konfirmasi_pemesanan.php?id=<?php echo $row['idpesan']; ?>"
                                                    class="btn btn-success btn-sm">Konfirmasi</a>
                                                <a href="batalkan_pemesanan.php?id=<?php echo $row['idpesan']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">Batalkan</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $no++;
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Bootstrap 5 JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>