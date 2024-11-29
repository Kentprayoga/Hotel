<?php
// Koneksi database menggunakan PDO
include '../config/db.php';

// Cek apakah parameter idpesan ada di URL
if (isset($_GET['idpesan'])) {
    $idpesan = $_GET['idpesan']; // Ambil ID Pesan dari URL

    // Ambil data pemesanan berdasarkan idpesan
    $sql = "SELECT totalbayar FROM pemesanan WHERE idpesan = :idpesan";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idpesan' => $idpesan]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $totalbayar = $result['totalbayar']; // Ambil totalbayar
    } else {
        die("Pesanan tidak ditemukan.");
    }

    // Proses pembayaran saat form disubmit
    if (isset($_POST['submit'])) {
        // Ambil data dari form
        $jumlah = $_POST['jumlah'];
        $bank = $_POST['bank'];
        $norek = $_POST['norek'];
        $namarek = $_POST['namarek'];

        // Proses upload gambar bukti pembayaran
        $gambar = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $target_dir = "../img/upload/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Membuat folder jika belum ada
        }

        // Menentukan ekstensi file gambar
        $file_extension = pathinfo($gambar, PATHINFO_EXTENSION); // Ambil ekstensi file
        // Pastikan ekstensi file adalah salah satu yang diizinkan (misalnya .jpg, .png)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            // Generate nama file unik dengan angka random dan timestamp
            $new_filename = uniqid('Bukti_pembayaran_', true) . '-' . time() . '.' . $file_extension; // Format nama file baru dengan ekstensi yang benar

            $target_file = $target_dir . $new_filename; // Path lengkap untuk menyimpan file

            if (move_uploaded_file($tmp_name, $target_file)) {
                $relative_path = $new_filename; // Menyimpan path relatif gambar

                // Query untuk menyimpan data pembayaran
                $sql = "INSERT INTO pembayaran (idpesan, jumlah, bank, norek, namarek, gambar) 
                        VALUES (:idpesan, :jumlah, :bank, :norek, :namarek, :gambar)";
                $stmt = $pdo->prepare($sql);

                try {
                    $pdo->beginTransaction(); // Memulai transaksi

                    // Eksekusi query untuk menyimpan data pembayaran
                    $stmt->execute([
                        ':idpesan' => $idpesan,
                        ':jumlah' => $jumlah,
                        ':bank' => $bank,
                        ':norek' => $norek,
                        ':namarek' => $namarek,
                        ':gambar' => $relative_path
                    ]);

                    // Update status pemesanan menjadi "Pending"
                    $sqlUpdate = "UPDATE pemesanan SET status = 'Pending' WHERE idpesan = :idpesan";
                    $stmtUpdate = $pdo->prepare($sqlUpdate);
                    $stmtUpdate->execute([':idpesan' => $idpesan]);

                    $pdo->commit(); // Commit transaksi

                    echo "Data pembayaran berhasil disimpan dan status pemesanan diperbarui!";
                } catch (PDOException $e) {
                    $pdo->rollBack(); // Jika ada error, rollback transaksi
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "Upload gambar gagal.";
            }
        } else {
            echo "Ekstensi file tidak valid. Pastikan file adalah gambar (jpg, jpeg, png, gif).";
        }
    }
} else {
    die("ID Pesan tidak ditemukan.");
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran</title>
    <!-- Menggunakan CDN Bootstrap 5.3 -->
    <?php require('view/link.php'); ?>
    <style>
    /* Menambahkan gaya hanya untuk class .form-container */
    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin-top: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn-custom {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        width: 100%;
        font-size: 16px;
    }

    .btn-custom:hover {
        background-color: #45a049;
    }

    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .alert-info {
        background-color: #e9ecef;
        color: #000;
        font-weight: bold;
    }

    .form-control-file {
        margin-top: 10px;
    }

    .custom-file-label {
        padding: 5px 10px;
        background-color: #f0f0f0;
        border-radius: 5px;
    }
    </style>
</head>

<body>
    <?php require('view/navbar.php'); ?>
    <div class="container form-container">
        <h2 class="title">Form Pembayaran</h2>

        <!-- Form pembayaran -->
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idpesan" value="<?php echo $idpesan; ?>">

            <div class="form-group">
                <label for="jumlah">Jumlah Pembayaran:</label>
                <input type="text" class="form-control" name="jumlah"
                    value="<?php echo number_format($totalbayar, 0, ',', '.'); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="bank">Bank:</label>
                <select name="bank" class="form-control" required>
                    <option value="BNI">BNI</option>
                    <option value="BCA">BCA</option>
                    <!-- Tambahkan pilihan bank lainnya sesuai kebutuhan -->
                </select>
            </div>

            <div class="form-group">
                <label for="norek">Nomor Rekening:</label>
                <input type="text" class="form-control" name="norek" required>
            </div>

            <div class="form-group">
                <label for="namarek">Nama Rekening:</label>
                <input type="text" class="form-control" name="namarek" required>
            </div>

            <div class="form-group">
                <label for="gambar">Bukti Pembayaran (Gambar):</label>
                <input type="file" class="form-control-file" name="gambar" required>
            </div>

            <button type="submit" name="submit" class="btn-custom">
                <i class="fas fa-paper-plane"></i> Kirim Pembayaran
            </button>
        </form>
    </div>

    <?php require('view/footer.php'); ?>
    <!-- Bootstrap JS -->
</body>

</html>