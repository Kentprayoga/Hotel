<?php
include 'config/db.php'; // Pastikan db.php sudah menginisialisasi $pdo
session_start();

class Kamar {
    private $pdo;

    // Constructor untuk menginisialisasi objek dan koneksi database
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fungsi untuk menampilkan data kamar
    public function getKamarData() {
        $sql = "SELECT kamar.idkamar, kamar.tipe, kamar.jumlah, kamar.harga, kamar.gambar, kamar.status, 
                        GROUP_CONCAT(fasilitas.nama_fasilitas SEPARATOR ', ') AS fasilitas
                FROM kamar
                LEFT JOIN kamar_fasilitas ON kamar.idkamar = kamar_fasilitas.idkamar
                LEFT JOIN fasilitas ON kamar_fasilitas.idfasilitas = fasilitas.idfasilitas
                GROUP BY kamar.idkamar";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    // Fungsi untuk mengupdate status kamar
    public function updateStatus($idkamar, $status) {
        $sql = "UPDATE kamar SET status = :status WHERE idkamar = :idkamar";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':idkamar', $idkamar);
        return $stmt->execute();
    }
}

$kamar = new Kamar($pdo);
$stmt = $kamar->getKamarData(); // Ambil data kamar

// Cek jika ada status yang ingin diubah
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['idkamar'])) {
    $idkamar = $_GET['idkamar'];
    $status = $_GET['status'];

    if ($kamar->updateStatus($idkamar, $status)) {
        echo "<script>alert('Status kamar berhasil diupdate!'); window.location.href = 'list_kamar.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status kamar!'); window.location.href = 'list_kamar.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <?php require ('./view/link.php'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<?php include "view/header.php"; ?>
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto">
            <div class="form-container">
                <div class="form-input">
                    <div class="card">
                        <div class="card-body">
                            <h2>Data Kamar</h2>
                            <div class="alert alert-info">DATA KAMAR</div>
                            <table width="100%" border="1" class="table table-bordered table-striped">
                                <tr>
                                    <th>No</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Gambar</th>
                                    <th>Fasilitas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>

                                <?php  
                                $no = 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td align="center"><?= $no; ?></td>
                                    <td align="center"><?= htmlspecialchars($row['tipe']); ?></td>
                                    <td align="center"><?= htmlspecialchars($row['jumlah']); ?></td>
                                    <td align="center"><?= htmlspecialchars($row['harga']); ?></td>
                                    <td align="center">
                                        <?php if (!empty($row['gambar'])): ?>
                                        <img src="../img/kamar/<?= htmlspecialchars($row['gambar']); ?>"
                                            alt="Gambar Kamar" width="100">
                                        <?php else: ?>
                                        Tidak ada gambar
                                        <?php endif; ?>
                                    </td>
                                    <td align="center"><?= htmlspecialchars($row['fasilitas']); ?></td>
                                    <td align="center">
                                        <?= htmlspecialchars($row['status']); ?>
                                        <br>
                                        <?php if ($row['status'] == 'Tersedia'): ?>
                                        <a href="?action=update_status&idkamar=<?= htmlspecialchars($row['idkamar']); ?>&status=tidak tersedia"
                                            class="btn btn-warning mt-2">
                                            <i class="fa fa-times-circle"></i> Tidak Tersedia
                                        </a>
                                        <?php else: ?>
                                        <a href="?action=update_status&idkamar=<?= htmlspecialchars($row['idkamar']); ?>&status=Tersedia"
                                            class="btn btn-success mt-2">
                                            <i class="fa fa-check-circle"></i> Tersedia
                                        </a>
                                        <?php endif; ?>
                                    </td>

                                    <td align="center">
                                        <a href="Update_kamar.php?idkamar=<?= htmlspecialchars($row['idkamar']); ?>"
                                            class="btn btn-primary">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <a href="hapuskamar.php?idkamar=<?= htmlspecialchars($row['idkamar']); ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                            <i class="fa fa-trash-alt"></i>
                                        </a>
                                    </td>

                                </tr>
                                <?php 
                                    $no++;
                                }
                                ?>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                <a href="Create_kamar.php" class="btn btn-success me-2">
                                    <i class="fa fa-plus-circle"></i> Tambah Kamar
                                </a>
                                <a href="List_fasilitas_kamar.php" class="btn btn-info">
                                    <i class="fa fa-cogs"></i> Fasilitas Kamar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./view/script.php'); ?>

</html>