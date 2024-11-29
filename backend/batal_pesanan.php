<?php
session_start();

// Koneksi database menggunakan PDO
include('../config/db.php'); 
// Pastikan pengguna sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Memeriksa apakah ID pesanan ada di URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $idpesan = $_GET['id'];

        // Query untuk mengambil data pesanan berdasarkan ID pesanan dan ID tamu
        $sql = "SELECT * FROM pemesanan WHERE idpesan = :idpesan AND idtamu = :idtamu";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
        $stmt->bindParam(':idtamu', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Memeriksa apakah pesanan ditemukan
        if ($stmt->rowCount() > 0) {
            // Mengambil data pesanan yang dibatalkan
            $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);
            $idkamar = $pesanan['idkamar'];
            $jumlah_dipesan = $pesanan['jumlah'];

            // Mengupdate status pesanan menjadi "Dibatalkan"
            $sql_update = "UPDATE pemesanan SET status = 'Dibatalkan' WHERE idpesan = :idpesan";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
            $stmt_update->execute();

            // Mengembalikan jumlah kamar yang dibatalkan ke tabel kamar
            $sql_check_kamar = "SELECT jumlah FROM kamar WHERE idkamar = :idkamar";
            $stmt_check_kamar = $pdo->prepare($sql_check_kamar);
            $stmt_check_kamar->bindParam(':idkamar', $idkamar, PDO::PARAM_INT);
            $stmt_check_kamar->execute();
            $kamar = $stmt_check_kamar->fetch(PDO::FETCH_ASSOC);

            if ($kamar) {
                // Menambahkan jumlah kamar yang dibatalkan
                $jumlah_kamar_sekarang = $kamar['jumlah'];
                $jumlah_kamar_baru = $jumlah_kamar_sekarang + $jumlah_dipesan;

                // Mengupdate jumlah kamar di tabel kamar
                $sql_update_kamar = "UPDATE kamar SET jumlah = :jumlah WHERE idkamar = :idkamar";
                $stmt_update_kamar = $pdo->prepare($sql_update_kamar);
                $stmt_update_kamar->bindParam(':jumlah', $jumlah_kamar_baru, PDO::PARAM_INT);
                $stmt_update_kamar->bindParam(':idkamar', $idkamar, PDO::PARAM_INT);
                $stmt_update_kamar->execute();
            }

            // Memberikan feedback kepada pengguna bahwa pesanan dibatalkan dan jumlah kamar dikembalikan
            echo "<script>
            alert('Pesanan Anda telah dibatalkan');
            window.location.href = './../user/reservasi.php'; // Redirect ke halaman reservasi
            </script>";
        } else {
            echo "<script>
            alert('Pesanan tidak ditemukan atau Anda tidak memiliki hak akses untuk membatalkan pesanan ini.');
            window.location.href = './../user/reservasi.php'; // Redirect ke halaman reservasi
            </script>";
        }
    } else {
        echo "<script>
        alert('ID Pesanan tidak ditemukan.');
        window.location.href = './../user/reservasi.php'; // Redirect ke halaman reservasi
        </script>";
    }
} else {
    echo "<script>
    alert('Anda harus login terlebih dahulu.');
    window.location.href = './../login.php'; // Redirect ke halaman login
    </script>";
}
?>