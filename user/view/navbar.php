<?php
require_once '../config/db.php';
require_once '../lib/otorisasi.php';
// Panggil fungsi checkRole untuk memastikan user memiliki role 'tamu'
checkRole('tamu'); // Hanya pengguna dengan role 'tamu' yang bisa mengakses halaman ini

// Mengambil nama file halaman yang sedang aktif
$halaman_aktif = basename($_SERVER['PHP_SELF']);

// Koneksi database untuk mengambil data pengguna
 // Pastikan file koneksi database Anda sudah benar

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Pengguna tidak terautentikasi.");
}

// Query untuk mengambil data pengguna dari database
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $fotoProfil = $user['Foto'] ?? 'default.jpg'; // Gunakan foto default jika tidak ada foto
    $nama_lengkap = $user['nama_lengkap'];
    $email = $user['email'];
    $nomor_hp = $user['nomor_hp'];
    $alamat = $user['alamat'];
} else {
    die("Data pengguna tidak ditemukan.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nabar</title>
    <link rel="icon" href="../img/home/logo.jpg" type="image/png"> <!-- Path ke gambar logo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
</head>

<body>
    <!-- Navbar HTML -->
    <nav
        class="navbar navbar-expand-lg navbar-dark custom-navbar custom-navbar-wrapper px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand h-font" href="index.php">
                <img src="../img/home/logo.jpg" alt="Hotel Grand Kenari" style="height: 50px;">
                Hotel Grand Kenari
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'index.php') ? 'active' : ''; ?>"
                            href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'List_kamar.php') ? 'active' : ''; ?>"
                            href="List_kamar.php">Kamar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'fasilitas.php') ? 'active' : ''; ?>"
                            href="fasilitas.php">Fasilitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'about.php') ? 'active' : ''; ?>"
                            href="about.php">About</a>
                    </li>

                    <!-- Link Reservasi hanya untuk pengguna dengan role 'tamu' -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'tamu'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'List_reservasi.php') ? 'active' : ''; ?>"
                            href="List_reservasi.php">Reservasi</a>
                    </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex">
                    <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'tamu'): ?>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../img/upload/<?= htmlspecialchars($fotoProfil) ?>" alt="Profile Picture"
                                class="rounded-circle" width="40" height="40">
                            <?= htmlspecialchars($nama_lengkap) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <a class="btn btn-login px-3" href="login.php" role="button">Log In</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>