<?php
// Pastikan session dimulai terlebih dahulu
require_once '../config/db.php';
require_once '../lib/otorisasi.php';
// Panggil fungsi checkRole untuk memastikan user memiliki role 'tamu'
checkRole('resepsionis'); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <?php require ('./view/link.php'); ?>
</head>
<style>
.h-font {
    font-family: 'Cardo', serif;
}

/* Menghilangkan margin dan padding default */
/* Menghilangkan margin dan padding default */
body,
html {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    /* Menghilangkan scroll horizontal */
    width: 100%;
    height: 100%;
}

/* Navbar tetap di atas */
.container-fluid.bg-dark.text-light {
    margin-bottom: 50px;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
    padding: 10px 20px;
    background-color: #343a40;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Menu Sidebar di kiri */
#dashboard-menu {

    position: fixed;
    top: 70px;
    /* Memberikan ruang agar tidak tertutup navbar */
    left: 0;
    /* Sidebar tetap di kiri */
    height: 100%;
    width: 280px;
    /* Lebar sidebar */
    z-index: 998;
    background-color: #343a40;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    padding: 0;
    overflow-y: auto;
}

/* Konten utama mengambil sisa lebar layar setelah sidebar */
#main-content {
    margin-left: 70px;
    /* Menambah ruang untuk sidebar */
    margin-top: 70px;
    /* Memberikan jarak dari navbar */
    padding: 20px;
    overflow-y: auto;
    width: calc(100% - 100px);
    /* Membuat lebar konten utama lebih besar */
    box-sizing: border-box;
}

/* Menyesuaikan ukuran sidebar jika perlu */

/* Menghilangkan scroll horizontal */
.container-fluid {
    width: 100%;
    padding: 0;
}

/* Responsif untuk perangkat dengan layar lebih kecil dari 992px */
@media screen and (max-width: 992px) {
    body {
        padding-top: 0;
        overflow-x: visible;
    }

    #dashboard-menu {
        width: 100%;
        /* Sidebar akan menggunakan seluruh lebar layar */
        position: relative;
        top: 0;
    }

    #main-content {
        margin-left: 0;
        padding: 20px;
        width: 100%;
    }

    .container-fluid.bg-dark.text-light {
        padding: 10px 15px;
        /* Agar lebih rapat di layar kecil */
    }
}
</style>

<body>
    <!-- HTML yang aman untuk admin -->
    <!-- header.php -->
    <!-- Navbar -->
    <div class="container-fluid bg-dark text-light p-3 d-flex justify-content-between align-items-center">
        <h2 class="mb-0 h-font">
            <i class="fa fa-hotel"></i> Hotel Grand Kenari
        </h2>
        <a href="logout.php" class="btn btn-light btn-sm">
            <i class="fa fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Sidebar -->
    <div id="dashboard-menu">
        <nav class="navbar navbar-expand-lg navbar-dark rounded shadow">
            <div class="container-fluid flex-lg-column align-items-stretch">
                <h4 class="mt-2 text-light">
                    <i class="fa fa-user-shield"></i> Admin
                </h4>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="dashboard.php">
                                <i class="fa fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="list_kamar.php">
                                <i class="fa fa-bed"></i> Kamar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="list_fasilitas_umum.php">
                                <i class="fa fa-cogs"></i> Fasilitas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="readuser.php">
                                <i class="fa fa-users"></i> Tamu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="List_pembayaran.php">
                                <i class="fa fa-credit-card"></i>konfirmasi Pembayaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="list_chekout.php">
                                <i class="fa fa-sign-out-alt"></i> Konfirmasi Checkout
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="list_pesanan.php">
                                <i class="fa fa-check-circle"></i> Transaksi Berhasil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="list_pesanan.php">
                                <i class="fa fa-times-circle"></i> Transaksi Dibatalkan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>



    <!-- Konten utama -->

    <?php require ('./view/script.php'); ?>
</body>

</html>