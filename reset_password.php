<?php
require_once 'config/db.php';
session_start();

// Pastikan pengguna datang dari halaman forgot_password.php
if (!isset($_SESSION['email_reset'])) {
    header('Location: forgot_password.php');
    exit;
}

$email = $_SESSION['email_reset']; // Ambil email dari session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($new_password === $confirm_password) {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashed_password, $email]);

        // Hapus email dari session setelah password berhasil direset
        unset($_SESSION['email_reset']);

        $_SESSION['message'] = "Password Anda telah berhasil direset. Silakan login dengan password baru.";
        header('Location: login.php');
        exit;
    } else {
        $_SESSION['error'] = "Password dan konfirmasi password tidak cocok!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2 class="text-center">Reset Password</h2>

                    <!-- Menampilkan pesan error atau sukses -->
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>