<?php
session_start();
require '../config/db.php'; // Pastikan path ini benar

// Cek apakah form login sudah disubmit
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk validasi admin
    $query = "SELECT * FROM admins WHERE username = :username"; 
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if (password_verify($password, $admin['password'])) {
            // Set session untuk admin
            session_regenerate_id(); // Untuk mencegah session hijacking
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['role'] = $admin['role'];

            // Arahkan ke dashboard admin
            header("Location: ../../admin/index.php");
            exit;
        } else {
            // Jika password salah
            $_SESSION['login_error'] = "Password yang Anda masukkan salah!";
            header("Location: ../../admin/login.php");
            exit;
        }
    } else {
        // Jika username tidak ditemukan
        $_SESSION['login_error'] = "Username tidak ditemukan!";
        header("Location: ../../admin/login.php");
        exit;
    }
}
?>