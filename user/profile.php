<?php
session_start();
// Ambil pesan error atau sukses jika ada
require_once '../config/db.php'; // Masukkan koneksi database Anda

// Pastikan ada user_id dalam session

$user_id = $_SESSION['user_id'];

// Query untuk mengambil data pengguna berdasarkan user_id
$query = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Data pengguna tidak ditemukan.";
    exit;
}

// Ambil data pengguna
$foto = $user['Foto']; // Path foto
$nama_lengkap = $user['nama_lengkap'];
$email = $user['email'];
$nomor_hp = $user['nomor_hp'];
$alamat = $user['alamat'];




?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php require('view/link.php'); ?>
    <!-- Tambahkan link CSS jika diperlukan -->
</head>
<style>
.profile-picture img {
    border-radius: 50%;
    object-fit: cover;
    /* Menjaga gambar tidak terdistorsi */
}

.card {
    border-radius: 15px;
    /* Membuat sudut card lebih halus */
    overflow: hidden;
    /* Menjaga tampilan gambar bulat dengan tepi yang rapi */
}

.card-body {
    padding: 20px;
    /* Memberikan jarak di dalam card */
    text-align: center;
}

.card-title {
    font-size: 1.5rem;
    /* Ukuran font untuk nama pengguna */
    font-weight: bold;
}

.btn-outline-primary {
    border-radius: 20px;
    /* Membuat tombol lebih bulat */
}
</style>

<body>
    <?php
// Periksa status di URL
if (isset($_GET['status'])) {
    $status = $_GET['status'];

    if ($status == 'success') {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Profil Anda berhasil diperbarui!',
                    showConfirmButton: false,
                    timer: 2000
                });
              </script>";
    } elseif ($status == 'error') {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan saat memperbarui profil.',
                    showConfirmButton: false,
                    timer: 2000
                });
              </script>";
    }
}
?>
    <!-- Tampilkan pesan sukses/error -->
    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?php echo $_SESSION['error_message']; ?>
        <?php unset($_SESSION['error_message']); ?>
    </div>
    <?php elseif (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success_message']; ?>
        <?php unset($_SESSION['success_message']); ?>
    </div>
    <?php endif; ?>
    <?php require('view/navbar.php'); ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Profil Pengguna</h1>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 text-center">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <!-- Menampilkan Foto Profil -->
                        <div class="profile-picture mb-4">
                            <img src="../img/upload/<?= htmlspecialchars($foto) ?>" alt="Foto Profil"
                                class="rounded-circle border border-3"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>

                        <!-- Info Profil -->
                        <div class="profile-info mb-4">
                            <h3 class="card-title"><?= htmlspecialchars($nama_lengkap) ?></h3>
                            <p class="text-muted"><?= htmlspecialchars($email) ?></p>
                            <p><strong>Nomor HP:</strong> <?= htmlspecialchars($nomor_hp) ?></p>
                            <p><strong>Alamat:</strong> <?= htmlspecialchars($alamat) ?></p>
                            <!-- Button untuk membuka Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                Ubah Password
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#profileModal">
                                edit profile
                            </button>

                            <div class="modal fade" id="changePasswordModal" tabindex="-1"
                                aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form untuk update password -->
                                            <form method="POST" action="update_password.php">
                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label">Password
                                                        Lama:</label>
                                                    <input type="password" class="form-control" id="current_password"
                                                        name="current_password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_password" class="form-label">Password Baru:</label>
                                                    <input type="password" class="form-control" id="new_password"
                                                        name="new_password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="confirm_password" class="form-label">Konfirmasi Password
                                                        Baru:</label>
                                                    <input type="password" class="form-control" id="confirm_password"
                                                        name="confirm_password" required>
                                                </div>
                                                <div class="mb-3 text-center">
                                                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- modal update -->
                            <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="profileModalLabel">Profil Pengguna</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="update_profile.php" method="POST"
                                                enctype="multipart/form-data">
                                                <!-- Foto Profil -->
                                                <div class="mb-3 text-center">
                                                    <img src="../img/upload/<?= htmlspecialchars($user['Foto']) ?>"
                                                        alt="Foto Profil" class="rounded-circle border border-3"
                                                        style="width: 150px; height: 150px; object-fit: cover;">
                                                </div>

                                                <!-- Input untuk mengubah foto -->
                                                <div class="mb-3">
                                                    <label for="foto" class="form-label">Ubah Foto Profil</label>
                                                    <input type="file" class="form-control" id="foto" name="foto">
                                                    <small class="form-text text-muted">Pilih file gambar untuk mengubah
                                                        foto profil.</small>
                                                </div>

                                                <!-- Nama Lengkap -->
                                                <div class="mb-3">
                                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                                    <input type="text" class="form-control" id="nama_lengkap"
                                                        name="nama_lengkap"
                                                        value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                                                </div>

                                                <!-- Email -->
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="<?= htmlspecialchars($user['email']) ?>" required>
                                                </div>

                                                <!-- Nomor HP -->
                                                <div class="mb-3">
                                                    <label for="nomor_hp" class="form-label">Nomor HP</label>
                                                    <input type="text" class="form-control" id="nomor_hp"
                                                        name="nomor_hp"
                                                        value="<?= htmlspecialchars($user['nomor_hp']) ?>" required>
                                                </div>

                                                <!-- Alamat -->
                                                <div class="mb-3">
                                                    <label for="alamat" class="form-label">Alamat</label>
                                                    <textarea class="form-control" id="alamat" name="alamat"
                                                        rows="3"><?= htmlspecialchars($user['alamat']) ?></textarea>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary">Perbarui
                                                        Profil</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require('view/footer.php'); ?>
    <!-- Link ke Bootstrap JS dan Popper (untuk Modal berfungsi) -->


</body>

</html>