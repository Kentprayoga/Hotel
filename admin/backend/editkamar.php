<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id = $_POST['id'];  // Asumsi ID kamar diterima di form
    $tipe = $_POST['tipe'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];

    // Cek jika ada gambar baru
    if (!empty($gambar)) {
        // Dapatkan gambar lama dari database
        $stmt = $pdo->prepare("SELECT gambar FROM kamar WHERE idkamar = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        // Jika gambar lama ada, hapus gambar tersebut
        if ($row && file_exists("../../img/kamar/" . $row['gambar'])) {
            unlink("../../img/kamar/" . $row['gambar']);
        }

        // Pindahkan gambar baru ke folder yang benar
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../../img/kamar/" . $gambar);

        // Update data kamar di database termasuk gambar
        $update = $pdo->prepare("UPDATE kamar SET tipe = :tipe, jumlah = :jumlah, harga = :harga, gambar = :gambar WHERE idkamar = :id");
        $update->execute(['tipe' => $tipe, 'jumlah' => $jumlah, 'harga' => $harga, 'gambar' => $gambar, 'id' => $id]);
    } else {
        // Jika tidak ada gambar baru, update data tanpa mengganti gambar
        $update = $pdo->prepare("UPDATE kamar SET tipe = :tipe, jumlah = :jumlah, harga = :harga WHERE idkamar = :id");
        $update->execute(['tipe' => $tipe, 'jumlah' => $jumlah, 'harga' => $harga, 'id' => $id]);
    }

    // Cek apakah update berhasil
    if ($update) {
        echo "<script>alert('Data kamar berhasil diperbarui'); window.location.href = '../kamar.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data kamar'); window.location.href = '../kamar.php';</script>";
    }
}
?>