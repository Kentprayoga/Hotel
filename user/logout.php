<?php
session_start(); // Mulai sesi

// Cek apakah session 'username' dan 'role' ada dan apakah role-nya 'user'
if (!isset($_SESSION['username'])) {
    // Jika session 'username' atau 'role' tidak ada atau bukan 'user', arahkan ke halaman login
    header("Location: ../login.php");
    exit;
}

// Hapus session yang terkait dengan user
unset($_SESSION['username']); // Menghapus session 'username'
unset($_SESSION['role']); // Menghapus session 'role'
unset($_SESSION['nama_lengkap']); // Menghapus session 'nama_lengkap', jika ada

// Menghancurkan seluruh session jika diperlukan (opsional)
// session_unset(); // Menghapus semua session
// session_destroy(); // Menghancurkan session

// Redirect ke halaman login setelah logout
header("Location: ../login.php");
exit; // Pastikan kode berikutnya tidak dieksekusi
?>