<?php
session_start();
require_once '../config/db.php';  // Pastikan koneksi database benar

// Pastikan form disubmit dengan POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $nomor_hp = htmlspecialchars(trim($_POST['nomor_hp']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));
    $role = 'tamu';  // Default role untuk user

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email tidak valid!'); window.location.href='../register.php';</script>";
        exit();
    }

    // Validasi jika ada input kosong
    if (empty($nama) || empty($email) || empty($username) || empty($password) || empty($nomor_hp) || empty($alamat)) {
        echo "<script>alert('Semua field harus diisi.'); window.location.href='../register.php';</script>";
        exit();
    }

    // Validasi file foto (jika ada upload)
    $foto = null;  // Default null jika tidak ada foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];  // Mendapatkan data file foto

        // Membuat nama file unik dengan time() dan md5(rand())
        $randomFilename = time() . '-' . md5(rand()) . '-' . $foto['name'];

        // Mendapatkan ekstensi file
        $file_ext = strtolower(pathinfo($randomFilename, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validasi ekstensi file
        if (!in_array($file_ext, $allowed_extensions)) {
            echo "<script>alert('Format file foto tidak valid.'); window.location.href='../register.php';</script>";
            exit();
        }

        // Tentukan folder untuk menyimpan file
        $upload_dir = '../img/upload/';
        $file_path = $upload_dir . $randomFilename;

        // Pindahkan file ke folder uploads
        if (!move_uploaded_file($foto['tmp_name'], $file_path)) {
            echo "<script>alert('Gagal mengunggah foto.'); window.location.href='../register.php';</script>";
            exit();
        }

        // Simpan hanya nama file foto, bukan path
        $foto = $randomFilename;
    } else {
        // Jika tidak ada foto, set null atau default
        $foto = null;
    }

    try {
        // Periksa apakah email atau username sudah ada
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Email sudah terdaftar.'); window.location.href='../register.php';</script>";
            exit();
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username sudah terdaftar.'); window.location.href='../register.php';</script>";
            exit();
        }

        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan ke database
        $sql = "INSERT INTO users (nama_lengkap, email, username, password, nomor_hp, alamat, role, Foto) 
                VALUES (:nama, :email, :username, :password, :nomor_hp, :alamat, :role, :foto)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':nomor_hp', $nomor_hp);
        $stmt->bindParam(':alamat', $alamat);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':foto', $foto);  // Menyimpan nama file foto

        if ($stmt->execute()) {
            header("Location: ../login.php");  // Pengalihan setelah sukses
            exit();
        } else {
            echo "<script>alert('Registrasi gagal, coba lagi.'); window.location.href='../register.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "'); window.location.href='../register.php';</script>";
    }
}
?>