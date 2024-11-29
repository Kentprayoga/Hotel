<?php
include "../config/db.php";  // Koneksi database

// Fungsi untuk menyimpan data kamar dan fasilitas
function simpanKamar($tipe, $jumlah, $harga, $gambar, $fasilitas = []) {
    global $pdo;

    // Tentukan lokasi folder tujuan untuk menyimpan gambar
    $targetDir = "../../img/kamar/";  // Pastikan path ini benar

    // Mengubah nama gambar menjadi nama acak
    $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    $newFileName = uniqid('kamar_', true) . '.' . $imageFileType;  // Nama acak dengan ekstensi yang sesuai
    $targetFile = $targetDir . $newFileName;

    // Validasi ekstensi file gambar (misalnya hanya .jpg, .jpeg, .png, .gif)
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi gambar
    if (!in_array($imageFileType, $validExtensions)) {
        return ['status' => 'error', 'message' => 'Hanya file gambar yang diperbolehkan.'];
    }

    // Cek apakah gambar sudah di-upload
    if ($_FILES['gambar']['error'] != UPLOAD_ERR_OK) {
        return ['status' => 'error', 'message' => 'Terjadi kesalahan saat meng-upload gambar.'];
    }

    try {
        // Simpan data kamar (status otomatis 'Tersedia')
        $sqlsimpan = $pdo->prepare("INSERT INTO kamar (tipe, jumlah, harga, gambar, status) VALUES (?, ?, ?, ?, 'Tersedia')");
        $sqlsimpan->execute([$tipe, $jumlah, $harga, $newFileName]);

        // Ambil ID kamar yang baru disimpan
        $id = $pdo->lastInsertId();

        // Memindahkan gambar ke folder tujuan
        if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            // Memindahkan gambar ke folder tujuan
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                // Menyimpan fasilitas yang dipilih jika ada
                if (!empty($fasilitas)) {
                    foreach ($fasilitas as $idfasilitas) {
                        // Simpan relasi antara kamar dan fasilitas
                        $sqlFasilitas = $pdo->prepare("INSERT INTO kamar_fasilitas (idkamar, idfasilitas) VALUES (?, ?)");
                        $sqlFasilitas->execute([$id, $idfasilitas]);
                    }
                }

                return ['status' => 'success', 'message' => 'Data Kamar Tersimpan dan Gambar Berhasil Diupload'];
            } else {
                return ['status' => 'error', 'message' => 'Gambar gagal dipindahkan ke folder tujuan.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Kesalahan upload gambar.'];
        }

    } catch (PDOException $e) {
        return ['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}

// Cek jika form di-submit dan data yang diperlukan ada
if (isset($_POST['tipe'], $_POST['jumlah'], $_POST['harga'], $_FILES['gambar'])) {
    // Ambil data dari form
    $tipe = $_POST['tipe'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];

    // Ambil fasilitas jika ada
    $fasilitas = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : [];

    // Panggil fungsi untuk menyimpan kamar
    $result = simpanKamar($tipe, $jumlah, $harga, $gambar, $fasilitas);

    // Redirect berdasarkan hasil
    if ($result['status'] == 'success') {
        header("Location: ../list_kamar.php?status=success");
    } else {
        header("Location: ../list_kamar.php?status=error&message=" . urlencode($result['message']));
    }
} else {
    header("Location: ../list_kamar.php?status=error&message=Semua form harus diisi.");
}
?>