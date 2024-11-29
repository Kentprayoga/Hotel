<?php
include 'config/db.php';

// Ambil ID fasilitas dari URL
if (isset($_GET['idfasilitas'])) {
    $idfasilitas = $_GET['idfasilitas'];

    // Ambil data fasilitas berdasarkan ID dari database
    try {
        $sql = "SELECT * FROM fasilitas_umu WHERE idf_fasilitas = :idfasilitas";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idfasilitas', $idfasilitas, PDO::PARAM_INT);
        $stmt->execute();
        $fasilitas = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
        exit;
    }

    // Proses ketika form disubmit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_fasilitas = $_POST['nama_fasilitas'];
        $deskripsi = $_POST['deskripsi'];
        $gambar_name = $fasilitas['gambar']; // Gambar tetap yang ada di database

        // Proses upload gambar jika ada gambar baru
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            // Tentukan direktori tujuan untuk gambar
            $target_dir = "../img/fasilitas/";  // Path relatif
            $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
            
            // Validasi ekstensi gambar yang diizinkan
            $allowed_types = array("jpg", "jpeg", "png", "gif");
            if (in_array($imageFileType, $allowed_types)) {
                // Membuat nama file gambar yang unik
                $gambar_name = uniqid() . '.' . $imageFileType;
                $target_file = $target_dir . $gambar_name;

                // Pindahkan gambar ke folder tujuan
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

        // Simpan perubahan fasilitas ke database
        try {
            $sql = "UPDATE fasilitas_umu SET nama_fasilitas = :nama_fasilitas, deskripsi = :deskripsi, gambar = :gambar WHERE idf_fasilitas = :idfasilitas";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nama_fasilitas', $nama_fasilitas);
            $stmt->bindParam(':deskripsi', $deskripsi);
            $stmt->bindParam(':gambar', $gambar_name);
            $stmt->bindParam(':idfasilitas', $idfasilitas, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                echo "<script>alert('Fasilitas berhasil diperbarui!'); window.location.href='list_fasilitas_umum.php';</script>";
            } else {
                echo "Terjadi kesalahan saat mengupdate fasilitas.";
            }
        } catch (PDOException $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }
} else {
    echo "ID fasilitas tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fasilitas Umum</title>
    <?php require ('./view/link.php'); ?>
</head>

<body>
    <?php include "view/header.php"; ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto">
                <div class="form-container">
                    <div class="form-input">
                        <div class="card">
                            <div class="card-body">
                                <h2>Edit Fasilitas Umum</h2>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="nama_fasilitas">Nama Fasilitas Umum</label>
                                        <input type="text" class="form-control" id="nama_fasilitas"
                                            name="nama_fasilitas"
                                            value="<?= htmlspecialchars($fasilitas['nama_fasilitas']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea class="form-control" id="deskripsi" name="deskripsi"
                                            required><?= htmlspecialchars($fasilitas['deskripsi']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="gambar">Gambar Fasilitas Umum</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar">
                                        <?php if (!empty($fasilitas['gambar'])): ?>
                                        <img src="../img/fasilitas/<?= htmlspecialchars($fasilitas['gambar']); ?>"
                                            width="100" alt="Gambar Fasilitas Umum">
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="list_fasilitas_umum.php" class="btn btn-secondary">Kembali</a>
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