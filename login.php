<?php
require_once 'config/db.php';
require('view/link.php');
session_start();

$errorMessages = [];

if (isset($_SESSION['login_error'])) {
    $errorMessages[] = $_SESSION['login_error'];
    unset($_SESSION['login_error']);  // Hapus pesan error setelah ditampilkan
}

if (isset($_SESSION['role_error'])) {
    $errorMessages[] = $_SESSION['role_error'];
    unset($_SESSION['role_error']);
}


?>


<style>
body {
    background-image: url('img/home/1.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh;
}

.bg-gradient {
    background: rgba(0, 0, 0, 0.5);
}

/* Menambahkan border ke card */
.card {
    border: 3px solid #ffffff;
    /* Ganti warna sesuai keinginan */
    /* Atur ketebalan border di sini */
    border-radius: 1rem;
    /* Radius border card */
}

@keyframes slideIn {
    0% {
        transform: translateY(-100px);
        opacity: 0;
    }

    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Styling untuk alert */
.alert {
    position: fixed;
    top: 20px;
    /* Sedikit dari atas */
    left: 50%;
    transform: translateX(-50%);
    /* Posisikan alert di tengah */
    background-color: #F44C59;
    /* Merah yang cerah */
    color: white;
    border-radius: 12px;
    padding: 15px 30px;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    /* Shadow lembut */
    z-index: 9999;
    animation: slideIn 0.5s ease-out;
    max-width: 80%;
    /* Batas lebar */
    width: auto;
    text-align: center;
    font-weight: 500;
    box-sizing: border-box;
}

.alert .btn-close {
    background-color: transparent;
    border: none;
    color: white;
    font-size: 18px;
    position: absolute;
    top: 5px;
    right: 10px;
    cursor: pointer;
}

.alert .btn-close:hover {
    color: #F4F4F4;
}

.alert strong {
    font-weight: bold;
}

.alert p {
    margin: 0;
    padding-top: 5px;
    font-size: 14px;
}
</style>


<body>
    <?php if (!empty($errorMessages)): ?>
    <script>
    window.onload = function() {
        // Loop untuk menampilkan semua pesan error yang ada
        <?php foreach ($errorMessages as $message): ?>
        var alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', 'alert-dismissible', 'fade', 'show');
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML =
            '<strong>Oops!</strong> <?= $message ?>' +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

        // Menambahkan alert ke body
        document.body.appendChild(alertDiv);
        <?php endforeach; ?>

        // Redirect setelah 5 detik
        setTimeout(function() {
            window.location = 'login.php'; // Redirect ke halaman login setelah 5 detik
        }, 5000); // Delay 5 detik
    }
    </script>
    <?php endif; ?>
    <section class="bg-gradient">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card text-white border border-2 " style="background-color: rgba(0, 123, 255, 0.5);">
                        <div class=" card-body p-5 text-center">
                            <form action="backend/login_proses.php" method="POST">
                                <div class="mb-md-5 mt-md-4">
                                    <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                    <p class="text-white-50 mb-5"></p>

                                    <!-- Menampilkan pesan error atau sukses -->
                                    <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                    </div>
                                    <?php endif; ?>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" id="username" name="username"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="username">Username:</label>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="password" id="password" name="password"
                                            class="form-control form-control-lg" required />
                                        <label class="form-label" for="password">Password:</label>
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit"
                                        name="submit">Login</button>
                                </div>
                                <div class="mt-4">
                                    <a href="forgot_password.php" class="text-white-50">Lupa Password?</a>
                                </div>
                                <div class="mt-4">
                                    <a href="register.php" class="text-white-50">Register</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>