<?php
include('../config/db.php'); // Pastikan file ini ada dan benar
include('../lib/Update_profile.php');
session_start(); // Mulai session untuk menampung pesan

$user_id = $_SESSION['user_id']; // Ambil user_id dari session
// Cek apakah form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil inputan dari form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    try {
        // Membuat objek Update
        $update = new Update($pdo, $user_id);

        // Panggil metode updatePassword untuk memperbarui password
        $update->updatePassword($current_password, $new_password);

        // Menampilkan pesan sukses menggunakan session
        $_SESSION['success_message'] = 'Password berhasil diperbarui!';
        header("Location: profile.php"); // Redirect ke halaman profil setelah update
        exit();

    } catch (Exception $e) {
        // Menampilkan pesan error jika terjadi kesalahan
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: profile.php"); // Redirect kembali ke halaman profil
        exit();
    }
}
?>