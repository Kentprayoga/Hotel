<?php
// Mulai sesi jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php'; // Pastikan path db.php benar

try {
    // Query untuk mendapatkan data kamar dan fasilitas
    $sql = "SELECT 
                k.idkamar, 
                k.tipe, 
                k.jumlah, 
                k.harga, 
                k.gambar, 
                k.status, 
                f.nama_fasilitas 
            FROM kamar k
            LEFT JOIN kamar_fasilitas kf ON k.idkamar = kf.idkamar
            LEFT JOIN fasilitas f ON f.idfasilitas = kf.idfasilitas
            WHERE k.status = 'Tersedia'";
    
    $stmt = $pdo->query($sql);
    
    // Jika ada data
    if ($stmt->rowCount() > 0) {
        $kamar_data = [];
        
        // Memproses data hasil query
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kamar_id = $row['idkamar'];
            
            // Jika kamar belum ada di array, tambahkan
            if (!isset($kamar_data[$kamar_id])) {
                $kamar_data[$kamar_id] = [
                    'idkamar' => $row['idkamar'],
                    'tipe' => $row['tipe'],
                    'jumlah' => $row['jumlah'],
                    'harga' => $row['harga'],
                    'gambar' => $row['gambar'],
                    'status' => $row['status'],
                    'fasilitas' => []
                ];
            }
            
            // Tambahkan fasilitas jika ada
            if ($row['nama_fasilitas']) {
                $kamar_data[$kamar_id]['fasilitas'][] = $row['nama_fasilitas'];
            }
        }
    } else {
        $kamar_data = []; // Jika tidak ada kamar
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>