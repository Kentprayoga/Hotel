<?php
include 'config/db.php';

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_fasilitas = $_POST['nama_fasilitas'];
    $deskripsi = $_POST['deskripsi'];

    // Proses upload gambar
    $gambar_name = '';  // Nama gambar yang akan disimpan di database

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        // Tentukan direktori tujuan untuk gambar (relative path)
        $target_dir = "../img/fasilitas/"; // Path relatif untuk file gambar
        $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        
        // Validasi ekstensi gambar yang diizinkan
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $allowed_types)) {
            // Membuat nama file gambar yang unik
            $gambar_name = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $gambar_name;

            // Cek apakah file berhasil dipindahkan
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                echo "Gambar berhasil diupload.";
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload gambar.";
                exit;
            }
        } else {
            echo "Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan.";
            exit;
        }
    }

    // Simpan data fasilitas ke database
    try {
        $sql = "INSERT INTO fasilitas_umu (nama_fasilitas, deskripsi, gambar) 
                VALUES (:nama_fasilitas, :deskripsi, :gambar)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nama_fasilitas', $nama_fasilitas);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':gambar', $gambar_name);
        
        if ($stmt->execute()) {
            echo "<script>alert('Fasilitas berhasil ditambahkan!'); window.location.href='list_fasilitas_umum.php';</script>";
        } else {
            echo "Terjadi kesalahan saat menambahkan fasilitas.";
        }
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas</title>
    <?php require ('./view/link.php'); ?>
</head>
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

<body>
    <?php include "view/header.php"; ?>
    <div class="container-fluid mt-3" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto">
                <div class="form-container">
                    <div class="form-input">
                        <div class="card">
                            <div class="card-body">
                                <h2>Tambah Fasilitas</h2>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <label for="nama_fasilitas">Nama Fasilitas:</label>
                                    <input type="text" name="nama_fasilitas" id="nama_fasilitas" required><br>

                                    <label for="deskripsi">Deskripsi:</label>
                                    <textarea name="deskripsi" id="deskripsi" required></textarea><br>

                                    <label for="gambar">Gambar:</label>
                                    <input type="file" name="gambar" id="gambar" accept="image/*" required><br>

                                    <button type="submit">Tambah Fasilitas</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('./view/script.php'); ?>
</body>

</html>