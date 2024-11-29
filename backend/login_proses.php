<?php
session_start();
require '../config/db.php'; // Pastikan path ini benar

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk validasi user
$query = "SELECT * FROM users WHERE username = :username"; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Set session setelah login berhasil
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Cek role user dan arahkan ke halaman yang sesuai
        switch ($user['role']) {
            case 'tamu':
                // Pengguna dengan role 'tamu' diarahkan ke halaman user
                header("Location: ../user/index.php");
                exit;
            case 'resepsionis':
                // Pengguna dengan role 'resepsionis' diarahkan ke halaman admin
                header("Location: ../admin/index.php");
                exit;
            default:
                // Jika tidak ada role yang dikenali, logout dan beri peringatan
                session_destroy();
                $_SESSION['login_error'] = "Role tidak dikenali.";
                header("Location: ../login.php");
                exit;
        }
    } else {
        // Jika password salah
        $_SESSION['login_error'] = "Password yang Anda masukkan salah!";
        header("Location: ../login.php");
        exit;
    }
} else {
    // Jika username tidak ditemukan
    $_SESSION['login_error'] = "Username tidak ditemukan!";
    header("Location: ../login.php");
    exit;
}
?>