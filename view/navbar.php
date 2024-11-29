<?php
require_once 'functions/db.php'; // Menghubungkan ke database jika diperlukan
session_start();
$halaman_aktif = basename($_SERVER['PHP_SELF']);
?>

<style>
/* Custom Navbar Styling for the specific page */
.custom-navbar-wrapper .custom-navbar {
    background-color: #007bff;
    padding: 15px 0;
}

.custom-navbar-wrapper .navbar-brand {
    color: #fff;
    font-weight: bold;
    font-size: 1.5rem;
    margin-left: 10px;
}

.custom-navbar-wrapper .navbar-nav .nav-link {
    color: #fff;
    margin: 0 10px;
}

.custom-navbar-wrapper .navbar-nav .nav-link:hover {
    color: #ffcc00;
}

.custom-navbar-wrapper .btn-login {
    background-color: #ffcc00;
    border: none;
    color: #007bff;
    font-weight: bold;
}

.custom-navbar-wrapper .btn-login:hover {
    background-color: #fff;
    color: #007bff;
}

.custom-navbar-wrapper .navbar-nav .nav-link.active {
    font-weight: bold;
    color: #ffcc00 !important;
    /* Warna teks link aktif */
}
</style>

<nav
    class="navbar navbar-expand-lg navbar-dark custom-navbar custom-navbar-wrapper px-lg-3 py-lg-2 shadow-sm sticky-top">
    <div class="container">
        <!-- Logo dan Nama Hotel -->
        <a class="navbar-brand h-font" href="index.php">
            <img src="img/home/logo.jpg" alt="Hotel Grand Kenari" style="height: 50px;">
            Hotel Grand Kenari
        </a>
        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($halaman_aktif == 'index.php') ? 'active' : ''; ?>"
                        href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($halaman_aktif == 'kamar.php') ? 'active' : ''; ?>"
                        href="kamar.php">Kamar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($halaman_aktif == 'fasilitas.php') ? 'active' : ''; ?>"
                        href="fasilitas.php">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($halaman_aktif == 'about.php') ? 'active' : ''; ?>"
                        href="about.php">About</a>
                </li>
            </ul>
            <!-- Login Button -->
            <div class="d-flex">
                <a class="btn btn-login px-3" href="login.php" role="button">Log In</a>
            </div>
        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-zgfMwXIXA6e/Adn+LsV6yPVBrk23CtMDRBBrgU4g/ehOUzqniJtvNW6AbPQPLgMp" crossorigin="anonymous">
</script>