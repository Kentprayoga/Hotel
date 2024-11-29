<?php
// Menyertakan file koneksi database
require_once('config/db.php'); // pastikan pathnya sesuai dengan file db.php Anda

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        echo "<script>alert('Data Kamar Tersimpan');</script>";
    } elseif ($_GET['status'] == 'error' && isset($_GET['message'])) {
        echo "<script>alert('Error: " . htmlspecialchars($_GET['message']) . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <?php require ('./view/link.php'); ?>
</head>
<style>
.h-font {
    font-family: 'Cardo', serif;
}

/* Membuat navbar tetap berada di atas saat di-scroll */
.container-fluid.bg-dark.text-light {
    position: fixed;
    /* Navbar tetap berada di atas */
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
    /* Sesuaikan dengan tinggi navbar */
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

/* Memberikan ruang di kiri untuk konten utama */

/* Responsif untuk perangkat dengan layar lebih kecil dari 992px */
@media screen and (max-width: 992px) {

    /* Menyesuaikan navbar agar tetap terlihat dengan lebar layar kecil */
    .container-fluid.bg-dark.text-light {
        position: relative;
        /* Navbar tidak fixed untuk layar kecil */
        padding: 10px 15px;
    }

    /* Menu samping disembunyikan dan tampilkan tombol hamburger */
    #dashboard-menu {
        position: relative;
        height: auto;
        width: 100%;
        top: 0;
        display: none;
        /* Menyembunyikan menu pada perangkat kecil */
    }

    /* Mengubah layout utama ketika menu samping disembunyikan */
    #main-content {
        margin-left: 0;
    }

    /* Menampilkan tombol hamburger */
    .navbar-toggler {
        display: block;
        /* Menampilkan tombol hamburger */
    }

    /* Membuat menu dropdown untuk mobile */
    .navbar-collapse {
        width: 100%;
        display: none;
        /* Menyembunyikan menu secara default */
    }

    .navbar-collapse.show {
        display: block;
        /* Menampilkan menu ketika hamburger diklik */
    }

    /* Menyesuaikan tampilan navbar untuk mobile */
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
<?php require('view/header.php'); ?>
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto">
            <div class="form-container">
                <h3 class="text-center mb-4 mt-3">Input Kamar</h3>
                <div class="form-input">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="backend/input.php" enctype="multipart/form-data">
                                <table class="table">
                                    <tr>
                                        <td>Tipe</td>
                                        <td><input type="text" required="required" name="tipe" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td><input type="number" required="required" name="jumlah" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Harga</td>
                                        <td><input type="number" required="required" name="harga" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Gambar</td>
                                        <td><input type="file" required="required" name="gambar" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fasilitas</td>
                                        <td>
                                            <?php
                                            // Koneksi ke database dan ambil data fasilitas
                                            $stmt = $pdo->query("SELECT * FROM fasilitas");
                                            while ($row = $stmt->fetch()) {
                                                echo '<div class="form-check">
                                                        <input type="checkbox" name="fasilitas[]" value="' . $row['idfasilitas'] . '" class="form-check-input">
                                                        <label class="form-check-label">' . htmlspecialchars($row['nama_fasilitas']) . '</label>
                                                    </div>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><button type="submit" class="btn btn-success w-100">Simpan</button></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require ('./view/script.php'); ?>

</html>