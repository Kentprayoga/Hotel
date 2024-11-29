<?php
// Koneksi database menggunakan PDO
$host = 'localhost'; 
$dbname = 'grand_kenari';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Memeriksa apakah ID pesanan ada di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idpesan = $_GET['id'];

    // Debugging: Cek apakah idpesan yang dikirim benar
    echo "ID Pesanan yang diterima: $idpesan<br>"; // Debugging untuk memastikan ID diterima dengan benar

    // Query untuk mengambil data pesanan berdasarkan ID pesanan
    $sql = "SELECT * FROM pemesanan WHERE idpesan = :idpesan";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
    $stmt->execute();

    // Memeriksa apakah pesanan ditemukan
    if ($stmt->rowCount() > 0) {
        // Mengambil data pesanan yang akan dibatalkan atau di-check-out
        $pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Cek hasil query untuk memastikan data ditemukan
        echo "<pre>";
        print_r($pesanan);
        echo "</pre>"; // Debugging untuk memverifikasi data pesanan

        $idkamar = $pesanan['idkamar'];
        $jumlah_dipesan = $pesanan['jumlah'];

        // Mengupdate status pesanan menjadi "Selesai" setelah check-out
        $sql_update = "UPDATE pemesanan SET status = 'Selesai' WHERE idpesan = :idpesan";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
        $stmt_update->execute();

        // Mengembalikan jumlah kamar yang digunakan ke tabel kamar
        $sql_check_kamar = "SELECT jumlah FROM kamar WHERE idkamar = :idkamar";
        $stmt_check_kamar = $pdo->prepare($sql_check_kamar);
        $stmt_check_kamar->bindParam(':idkamar', $idkamar, PDO::PARAM_INT);
        $stmt_check_kamar->execute();
        $kamar = $stmt_check_kamar->fetch(PDO::FETCH_ASSOC);

        if ($kamar) {
            // Menambahkan jumlah kamar yang digunakan
            $jumlah_kamar_sekarang = $kamar['jumlah'];
            $jumlah_kamar_baru = $jumlah_kamar_sekarang + $jumlah_dipesan;

            // Mengupdate jumlah kamar di tabel kamar
            $sql_update_kamar = "UPDATE kamar SET jumlah = :jumlah WHERE idkamar = :idkamar";
            $stmt_update_kamar = $pdo->prepare($sql_update_kamar);
            $stmt_update_kamar->bindParam(':jumlah', $jumlah_kamar_baru, PDO::PARAM_INT);
            $stmt_update_kamar->bindParam(':idkamar', $idkamar, PDO::PARAM_INT);
            $stmt_update_kamar->execute();
        }

        // Memberikan feedback kepada admin bahwa check-out berhasil
        echo "<script>
        alert('Check-out berhasil dan jumlah kamar telah dikembalikan.');
        window.location.href = './../admin/list_chekout.php'; // Redirect ke halaman daftar pemesanan
        </script>";
    } else {
        echo "<script>
        alert('Pesanan tidak ditemukan.');
        window.location.href = './../admin/list_chekout.php'; // Redirect ke halaman daftar pemesanan
        </script>";
    }
} else {
    echo "<script>
    alert('ID Pesanan tidak ditemukan.');
    window.location.href = './../admin/list_chekout.php'; // Redirect ke halaman daftar pemesanan
    </script>";
}
?>