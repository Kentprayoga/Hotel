<?php  
include 'config/db.php';

// Query untuk mengambil data fasilitas dari database
$sql = "SELECT * FROM fasilitas";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$no = 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<?php 
    $no++;
}
?>
<?php require ('./view/link.php'); 
?>
<?php include "view/header.php"; ?>
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto">
            <div class="form-container">
                <div class="form-input">
                    <div class="card">
                        <div class="card-body">
                            <h2>Fasilitas Kamar</h2>
                            <div class="alert alert-info mb-3">DATA FASILITAS</div>
                            <a href="Create_fasilitas.php" class="btn btn-primary">Tambah Data</a>
                            <table width="100%" border="1" class="table table-bordered table-striped">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Aksi</th>
                                </tr>


                                <tr>
                                    <td align="center"><?= $no; ?></td>
                                    <td align="center"><?= htmlspecialchars($row['nama_fasilitas']); ?></td>
                                    <td align="center">
                                        <a href="Update_fasilitas_kamar.php?idfasilitas=<?= htmlspecialchars($row['idfasilitas']); ?>"
                                            class="btn btn-success">Edit</a>
                                        <a href="backend/Dlete_fasilitas_kamar.php?idfasilitas=<?= htmlspecialchars($row['idfasilitas']); ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus fasilitas ini?')">Hapus</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>