<?php
// Include koneksi database
include 'config/db.php';

// Query untuk mengambil semua fasilitas
$sql = "SELECT * FROM fasilitas_umu";
$stmt = $pdo->query($sql);

// Ambil semua data fasilitas dan simpan dalam array
$fasilitas_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Fasilitas</title>
    <!-- Link Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                <h2 class="mb-4">Daftar Fasilitas</h2>

                                <?php if (count($fasilitas_list) > 0): ?>
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nama Fasilitas</th>
                                            <th>Deskripsi</th>
                                            <th>Gambar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fasilitas_list as $fasilitas): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fasilitas['nama_fasilitas']) ?></td>
                                            <td><?= htmlspecialchars($fasilitas['deskripsi']) ?></td>
                                            <td>
                                                <img src="../img/fasilitas/<?= htmlspecialchars($fasilitas['gambar']); ?>"
                                                    width="100" alt="Gambar Fasilitas Umum">
                                            </td>
                                            <td>
                                                <a href="Update_fasilitas_umum.php?idfasilitas=<?= $fasilitas['idf_fasilitas'] ?>"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <a href="delete_fasilitas_umum.php?idfasilitas=<?= $fasilitas['idf_fasilitas'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <p>Tidak ada fasilitas yang tersedia.</p>
                                <?php endif; ?>

                                <a href="create_fasilitas_umum.php" class="btn btn-primary">Tambah Fasilitas</a>

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