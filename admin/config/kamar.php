<?php
class Kamar {
    private $pdo;

    // Constructor untuk inisialisasi koneksi DB
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fungsi untuk mendapatkan data kamar berdasarkan ID
    public function getKamarById($idkamar) {
        $query = $this->pdo->prepare("SELECT * FROM kamar WHERE idkamar = ?");
        $query->execute([$idkamar]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk mengupdate data kamar
    public function updateKamar($idkamar, $tipe, $jumlah, $harga, $gambar = null) {
        $sqlUpdate = "UPDATE kamar SET tipe = ?, jumlah = ?, harga = ?";
        $params = [$tipe, $jumlah, $harga];

        // Jika ada gambar baru, tambahkan gambar ke query
        if ($gambar) {
            $sqlUpdate .= ", gambar = ?";
            $params[] = $gambar;
        }

        $sqlUpdate .= " WHERE idkamar = ?";
        $params[] = $idkamar;

        // Eksekusi update
        $query = $this->pdo->prepare($sqlUpdate);
        $query->execute($params);
    }

    // Fungsi untuk mengelola fasilitas kamar
    public function updateFasilitas($idkamar, $fasilitas) {
        // Hapus fasilitas lama
        $sqlDeleteFasilitas = $this->pdo->prepare("DELETE FROM kamar_fasilitas WHERE idkamar = ?");
        $sqlDeleteFasilitas->execute([$idkamar]);

        // Simpan fasilitas baru
        if ($fasilitas) {
            foreach ($fasilitas as $idfasilitas) {
                $sqlFasilitas = $this->pdo->prepare("INSERT INTO kamar_fasilitas (idkamar, idfasilitas) VALUES (?, ?)");
                $sqlFasilitas->execute([$idkamar, $idfasilitas]);
            }
        }
    }

    // Fungsi untuk menghapus fasilitas kamar
    public function deleteFasilitas($idkamar) {
        $sqlDeleteFasilitas = $this->pdo->prepare("DELETE FROM kamar_fasilitas WHERE idkamar = ?");
        $sqlDeleteFasilitas->execute([$idkamar]);
    }

    // Fungsi untuk menghapus kamar
    public function deleteKamar($idkamar) {
        $sqlDeleteKamar = $this->pdo->prepare("DELETE FROM kamar WHERE idkamar = ?");
        $sqlDeleteKamar->execute([$idkamar]);
    }

    // Fungsi untuk memproses gambar
    public function uploadGambar($file) {
        $targetDir = "../img/kamar/";
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid('kamar_', true) . '.' . $imageFileType;  // Nama acak dengan ekstensi yang sesuai
        $targetFile = $targetDir . $newFileName;

        // Validasi ekstensi file gambar
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $validExtensions)) {
            throw new Exception('Hanya file gambar yang diperbolehkan.');
        }

        // Cek apakah gambar sudah di-upload
        if ($file['error'] != UPLOAD_ERR_OK) {
            throw new Exception('Terjadi kesalahan saat meng-upload gambar.');
        }

        // Memindahkan gambar ke folder tujuan
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception('Gambar gagal dipindahkan ke folder tujuan.');
        }

        return $newFileName;
    }

    // Fungsi untuk menghapus gambar dari folder
    public function deleteGambar($gambar) {
        $gambarPath = "../img/kamar/" . $gambar;
        if (file_exists($gambarPath)) {
            unlink($gambarPath);
        }
    }

    // Fungsi untuk menyimpan data kamar
    public function simpanKamar($tipe, $jumlah, $harga, $gambar, $fasilitas = []) {
        try {
            // Simpan data kamar (status otomatis 'Tersedia')
            $sqlSimpan = $this->pdo->prepare("INSERT INTO kamar (tipe, jumlah, harga, gambar, status) VALUES (?, ?, ?, ?, 'Tersedia')");
            $sqlSimpan->execute([$tipe, $jumlah, $harga, $gambar]);

            // Ambil ID kamar yang baru disimpan
            $id = $this->pdo->lastInsertId();

            // Menyimpan fasilitas yang dipilih jika ada
            if (!empty($fasilitas)) {
                foreach ($fasilitas as $idfasilitas) {
                    // Simpan relasi antara kamar dan fasilitas
                    $sqlFasilitas = $this->pdo->prepare("INSERT INTO kamar_fasilitas (idkamar, idfasilitas) VALUES (?, ?)");
                    $sqlFasilitas->execute([$id, $idfasilitas]);
                }
            }

            return ['status' => 'success', 'message' => 'Data Kamar Tersimpan dengan Gambar'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }
}
?>