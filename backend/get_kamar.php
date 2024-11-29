<?php
include('config/db.php');

$sql = "SELECT k.idkamar, k.tipe, k.jumlah, k.harga, k.gambar, f.nama_fasilitas 
        FROM kamar k
        LEFT JOIN kamar_fasilitas kf ON k.idkamar = kf.idkamar
        LEFT JOIN fasilitas f ON f.idfasilitas = kf.idfasilitas";

$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    $kamar_data = [];
    
    // Mengambil semua data kamar dan fasilitasnya
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $kamar_id = $row['idkamar'];
        
        // Jika kamar belum ada di array, buat entry baru untuk kamar tersebut
        if (!isset($kamar_data[$kamar_id])) {
            $kamar_data[$kamar_id] = [
                'idkamar' => $row['idkamar'],
                'tipe' => $row['tipe'],
                'jumlah' => $row['jumlah'],
                'harga' => $row['harga'],
                'gambar' => $row['gambar'],
                'fasilitas' => []
            ];
        }
        
        // Menambahkan fasilitas ke kamar yang sudah ada
        if ($row['nama_fasilitas']) {
            $kamar_data[$kamar_id]['fasilitas'][] = $row['nama_fasilitas'];
        }
    }
} else {
    echo "<p>No rooms available.</p>";
}
?>