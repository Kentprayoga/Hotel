<?php
session_start();
include('../config/db.php'); // Pastikan koneksi sudah terhubung dengan database
include('../lib/Update_profile.php');
$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Cek apakah form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil inputan dari form
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $nomor_hp = $_POST['nomor_hp'];
    $alamat = $_POST['alamat'];
    $foto = $_FILES['foto']; // Foto Profil

    try {
        // Membuat objek Update
        $update = new Update($pdo, $user_id);

        // Update foto profil jika ada
        if ($foto && $foto['error'] == 0) {
            $update->updateFotoProfil($foto);
        }

        // Update data profil pengguna
        $update->updateUserProfile($nama_lengkap, $email, $nomor_hp, $alamat);

        // Menampilkan pesan sukses menggunakan session
        $_SESSION['success_message'] = 'Profil berhasil diperbarui!';
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