<?php
// Menetapkan zona waktu ke WIB (Waktu Indonesia Barat)
date_default_timezone_set('Asia/Jakarta');

// Koneksi ke database menggunakan PDO
$host = 'localhost'; 
$dbname = 'grand_kenari'; 
$username = 'root'; 
$password = ''; 

// Cek koneksi database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

session_start();

// Periksa apakah ID kamar ada di URL dan valid
if (isset($_GET['idkamar']) && is_numeric($_GET['idkamar'])) {
    $idkamar = $_GET['idkamar'];

    // Query untuk mengambil detail kamar berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM kamar WHERE idkamar = :idkamar");
    $stmt->execute([':idkamar' => $idkamar]);
    $kamarDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika kamar tidak ditemukan
    if (!$kamarDetails) {
        die('Kamar tidak ditemukan.');
    }
} else {
    die('ID kamar tidak valid atau tidak ditemukan.');
}

// Fungsi untuk memesan kamar
function buatPemesanan($idkamar, $iduser, $tglmasuk, $tglkeluar, $jumlah, $lamahari, $totalbayar) {
    global $pdo;

    // Mengambil data kamar berdasarkan idkamar
    $sqlKamar = "SELECT harga, jumlah, status FROM kamar WHERE idkamar = :idkamar";
    $stmtKamar = $pdo->prepare($sqlKamar);
    $stmtKamar->execute([':idkamar' => $idkamar]);
    $kamar = $stmtKamar->fetch(PDO::FETCH_ASSOC);

    if ($kamar && $kamar['harga'] && $kamar['status'] == 'Tersedia') {
        $harga = $kamar['harga']; // Ambil harga kamar dari database

        // Waktu pemesanan (gunakan waktu lokal)
        $tglPesan = date('Y-m-d H:i:s');  // Waktu lokal
        // Waktu batas bayar (30 menit setelah pemesanan)
        $batasBayar = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $status = 'Menunggu Pembayaran'; // Status pemesanan

        // Hitung total bayar
        $totalbayar = $harga * $jumlah * $lamahari; // Perhitungan total bayar (harga kamar * jumlah kamar * lama hari)

        // Insert data pemesanan ke tabel pemesanan
        $sqlInsert = "INSERT INTO pemesanan (tglpesan, batasbayar, idkamar, jumlah, idtamu, tglmasuk, tglkeluar, lamahari, totalbayar, status) 
                      VALUES (:tglPesan, :batasBayar, :idkamar, :jumlah, :idtamu, :tglmasuk, :tglkeluar, :lamahari, :totalbayar, :status)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            ':tglPesan' => $tglPesan,
            ':batasBayar' => $batasBayar,
            ':idkamar' => $idkamar,
            ':jumlah' => $jumlah,
            ':idtamu' => $iduser,
            ':tglmasuk' => $tglmasuk,
            ':tglkeluar' => $tglkeluar,
            ':lamahari' => $lamahari,
            ':totalbayar' => $totalbayar,
            ':status' => $status
        ]);

        // Update jumlah kamar yang tersedia
        $newJumlah = $kamar['jumlah'] - $jumlah;
        $sqlUpdate = "UPDATE kamar SET jumlah = :newJumlah WHERE idkamar = :idkamar";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([':newJumlah' => $newJumlah, ':idkamar' => $idkamar]);

        echo "Pemesanan berhasil!";
    } else {
        echo "Kamar tidak tersedia atau harga tidak ditemukan.";
    }
}

// Menangani pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idkamar = $_POST['idkamar']; 
    $tglmasuk = $_POST['tglmasuk']; 
    $tglkeluar = $_POST['tglkeluar']; 
    $jumlah = $_POST['jumlah']; 
    $lamahari = (strtotime($tglkeluar) - strtotime($tglmasuk)) / (60 * 60 * 24); 

    // Memeriksa ketersediaan kamar pada tanggal yang dipilih
    $sqlCheckAvailability = "
        SELECT COUNT(*) FROM pemesanan 
        WHERE idkamar = :idkamar 
        AND ((tglmasuk BETWEEN :tglmasuk AND :tglkeluar) OR (tglkeluar BETWEEN :tglmasuk AND :tglkeluar) 
        OR (:tglmasuk BETWEEN tglmasuk AND tglkeluar) OR (:tglkeluar BETWEEN tglmasuk AND tglkeluar))
        AND status != 'Batal'"; // pastikan memeriksa pemesanan yang statusnya bukan 'Batal'

    $stmtCheck = $pdo->prepare($sqlCheckAvailability);
    $stmtCheck->execute([
        ':idkamar' => $idkamar,
        ':tglmasuk' => $tglmasuk,
        ':tglkeluar' => $tglkeluar
    ]);

    $availability = $stmtCheck->fetchColumn();

    if ($availability > 0) {
        echo "Kamar ini sudah terpesan pada tanggal tersebut.";
        exit; // Menghentikan eksekusi lebih lanjut
    }

    // Ambil harga kamar dari form
    $hargaKamar = $_POST['harga'];
    $totalbayar = (int)($hargaKamar * $jumlah * $lamahari); // Pastikan total bayar adalah integer

    buatPemesanan($idkamar, $_SESSION['user_id'], $tglmasuk, $tglkeluar, $jumlah, $lamahari, $totalbayar);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Kamar</title>
    <?php require('view/link.php'); ?>
</head>

<body>
    <?php require('view/navbar.php'); ?>
    <div class="container mt-5">
        <form method="POST" action="">
            <input type="hidden" name="idkamar" value="<?php echo $kamarDetails['idkamar']; ?>">

            <div class="mb-3">
                <label for="tipe" class="form-label">Tipe Kamar:</label>
                <input type="text" id="tipe" class="form-control" value="<?php echo $kamarDetails['tipe']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="harga" class="form-label">Harga Kamar:</label>
                <input type="text" name="harga" id="harga" class="form-control"
                    value="<?php echo (int)$kamarDetails['harga']; ?>" readonly>
            </div>

            <div class="mb-3">
                <div class="d-flex">
                    <img src="../img/kamar/<?php echo $kamarDetails['gambar']; ?>" alt="Gambar Kamar" class="img-fluid"
                        style="max-width: 300px; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                </div>
            </div>

            <!-- Status Ketersediaan -->
            <div id="statusKetersediaan" class="mb-3"></div>

            <div class="mb-3">
                <label for="tglmasuk" class="form-label">Tanggal Masuk:</label>
                <input type="date" name="tglmasuk" id="tglmasuk" class="form-control" min="<?= date('Y-m-d'); ?>"
                    required onchange="updateLamaHari()">
            </div>

            <div class="mb-3">
                <label for="tglkeluar" class="form-label">Tanggal Keluar:</label>
                <input type="date" name="tglkeluar" id="tglkeluar" class="form-control"
                    min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required onchange="updateLamaHari()">
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah Kamar:</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required
                    oninput="updateTotalBayar()">
            </div>

            <div class="mb-3">
                <label for="lamahari" class="form-label">Lama Hari:</label>
                <input type="text" name="lamahari" id="lamahari" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label for="totalbayar" class="form-label">Total Bayar:</label>
                <input type="number" name="totalbayar" id="totalbayar" class="form-control" readonly>
            </div>

            <button type="submit" class="btn btn-primary">Pesan Kamar</button>
        </form>
    </div>
    <?php require('view/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Fungsi untuk menghitung lama hari dan total bayar secara otomatis
    function updateTotalBayar() {
        const harga = document.getElementById('harga').value;
        const jumlah = document.getElementById('jumlah').value;
        const tglmasuk = document.getElementById('tglmasuk').value;
        const tglkeluar = document.getElementById('tglkeluar').value;

        if (tglmasuk && tglkeluar && jumlah && harga) {
            const date1 = new Date(tglmasuk);
            const date2 = new Date(tglkeluar);

            const diffTime = Math.abs(date2 - date1);
            const lamahari = diffTime / (1000 * 60 * 60 * 24); // Menghitung selisih hari

            document.getElementById('lamahari').value = lamahari;

            const totalBayar = Math.floor(harga * jumlah * lamahari); // Menghitung total bayar
            document.getElementById('totalbayar').value = totalBayar; // Menampilkan total bayar
        }
    }

    // Fungsi untuk menghitung lama hari secara otomatis saat tanggal berubah
    function updateLamaHari() {
        const tglmasuk = document.getElementById('tglmasuk').value;
        const tglkeluar = document.getElementById('tglkeluar').value();

        if (tglmasuk && tglkeluar) {
            const date1 = new Date(tglmasuk);
            const date2 = new Date(tglkeluar);

            const diffTime = Math.abs(date2 - date1);
            const lamahari = diffTime / (1000 * 60 * 60 * 24);

            document.getElementById('lamahari').value = lamahari;
            updateTotalBayar(); // Update total bayar setelah lama hari dihitung
        }
    }
    </script>
</body>

</html>