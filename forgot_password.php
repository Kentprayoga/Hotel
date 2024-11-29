<?php
require_once 'config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil email dari form
    $email = $_POST['email'];

    // Cek apakah email ada dalam database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Email ditemukan, tampilkan form reset password
        $_SESSION['email_reset'] = $email; // Menyimpan email untuk proses reset
        header('Location: reset_password.php');
        exit;
    } else {
        $_SESSION['error'] = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <h2 class="text-center">Lupa Password</h2>

                    <!-- Menampilkan pesan error atau sukses -->
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Anda:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>