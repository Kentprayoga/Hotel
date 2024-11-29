<?php
// Koneksi ke database menggunakan PDO
$host = 'localhost'; // Host database
$dbname = 'grand_kenari'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Query untuk mengambil data dari tabel fasilitas_umu
$sql = "SELECT * FROM fasilitas_umu";
$stmt = $pdo->query($sql);

// Menyimpan hasil query dalam array
$fasilitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah ada data
if ($fasilitas) {
    // Data berhasil diambil
} else {
    echo "Tidak ada data fasilitas.";
}
$pdo = null;  // Menutup koneksi database
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas</title>
    <?php require('view/link.php');?>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<style>
.pop:hover {
    border-top-color: var(--warna1) !important;
    transform: scale(1.03);
    transition: all;
}
</style>

<body>

    <!-- navbar -->
    <?php require('view/navbar.php');?>
    <!-- navbar end -->

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Fasilitas Kami</h2>
        <hr>
        <p class="text-center mt-3">Kami memiliki fasilitas dan perlengkapan fisik yang dapat memudahkan anda dalam
            melakukan aktivitas. <br> Rasakan kenyamanan maksimal dengan fasilitas lengkap di hotel kami, dan nikmati
            pengalaman menginap yang tak terlupakan dengan pelayanan ramah dan suasana yang nyaman, dirancang untuk
            memenuhi segala kebutuhan Anda selama berlibur atau berbisnis!</p>
    </div>

    <div class="container">
        <div class="row">
            <?php foreach ($fasilitas as $f): ?>
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="img/fasilitas/<?php echo $f['gambar']; ?>" width="40px">
                        <h5 class="m-0 ms-3"><?php echo $f['nama_fasilitas']; ?></h5>
                    </div>
                    <p><?php echo $f['deskripsi']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <?php require('view/footer.php');?>
    <!-- footer end -->

    <!-- js -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>