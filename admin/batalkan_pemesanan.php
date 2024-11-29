<?php
// Koneksi ke database
include('../config/db.php');

// Ambil ID pesanan dari URL
$idpesan = $_GET['id'];

// Pastikan ID valid
if (isset($idpesan) && is_numeric($idpesan)) {
    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Query untuk mengambil data jumlah kamar yang dipesan dan stok kamar
        $sql = "SELECT p.idkamar, p.jumlah AS jumlah_dipesan, k.jumlah AS stok_kamar
                FROM pemesanan p
                JOIN kamar k ON p.idkamar = k.idkamar
                WHERE p.idpesan = :idpesan";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Ambil data jumlah kamar yang dipesan dan stok kamar
            $idkamar = $data['idkamar'];
            $jumlah_dipesan = $data['jumlah_dipesan'];
            $stok_kamar = $data['stok_kamar'];

            // Tambahkan jumlah kamar yang dibatalkan ke stok kamar
            $stok_kamar_baru = $stok_kamar + $jumlah_dipesan;

            // Query untuk update jumlah kamar yang tersedia
            $sql_update_kamar = "UPDATE kamar SET jumlah = :stok_kamar WHERE idkamar = :idkamar";
            $stmt_update_kamar = $pdo->prepare($sql_update_kamar);
            $stmt_update_kamar->bindParam(':stok_kamar', $stok_kamar_baru, PDO::PARAM_INT);
            $stmt_update_kamar->bindParam(':idkamar', $idkamar, PDO::PARAM_INT);
            $stmt_update_kamar->execute();

            // Query untuk mengupdate status pesanan menjadi 'Dibatalkan'
            $sql_update_pemesanan = "UPDATE pemesanan SET status = 'Dibatalkan' WHERE idpesan = :idpesan";
            $stmt_update_pemesanan = $pdo->prepare($sql_update_pemesanan);
            $stmt_update_pemesanan->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
            $stmt_update_pemesanan->execute();

            // Commit transaksi
            $pdo->commit();

            // Redirect ke halaman utama setelah berhasil mengupdate
            header("Location: List_pembayaran.php?status=batalkan-success");
            exit();
        } else {
            // Jika tidak ditemukan pesanan
            throw new Exception('Pesanan tidak ditemukan.');
        }
    } catch (Exception $e) {
        // Jika terjadi error, rollback transaksi
        $pdo->rollBack();
        // Redirect ke halaman utama dengan pesan error
        header("Location: List_pembayaran.php?status=batalkan-failed");
        exit();
    }
} else {
    // Redirect jika ID tidak valid
    header("Location: List_pembayaran.php?status=invalid-id");
    exit();
}
?>