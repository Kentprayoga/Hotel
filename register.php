<?php 
require_once 'config/db.php';  // Pastikan path ini benar
require('view/link.php'); // Jika Anda punya file link.php untuk memasukkan CSS/JS
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
    border-radius: 1rem;
}
</style>

<body>
    <section class="bg-gradient">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-6">
                    <div class="card text-white border border-2 " style="background-color: rgba(0, 123, 255, 0.5);">
                        <div class="card-body p-4">
                            <h2 class="fw-bold mb-4 text-uppercase text-center">Register</h2>
                            <p class="text-white-50 mb-5 text-center">Masukkan data sesuai KTP</p>

                            <!-- Form untuk registrasi -->
                            <form action="backend/register_proses.php" method="POST" enctype="multipart/form-data"
                                id="registerForm">
                                <div class="row mb-4">
                                    <div class="col-12 col-md-6">
                                        <div class="form-outline form-white">
                                            <label class="form-label" for="nama">Nama Lengkap</label>
                                            <input type="text" name="nama" id="nama"
                                                class="form-control form-control-lg" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-outline form-white">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-lg" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12 col-md-6">
                                        <div class="form-outline form-white">
                                            <label class="form-label" for="username">Username</label>
                                            <input type="text" id="username" name="username"
                                                class="form-control form-control-lg" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-outline form-white">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" id="password" name="password"
                                                class="form-control form-control-lg" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12 col-md-6">
                                        <div class="form-outline form-white">
                                            <label class="form-label" for="nomor_hp">Nomor HP</label>
                                            <input type="text" id="nomor_hp" name="nomor_hp"
                                                class="form-control form-control-lg" required />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-outline form-white">
                                            <label class="form-label" for="alamat">Alamat</label>
                                            <textarea id="alamat" name="alamat" class="form-control form-control-lg"
                                                required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input foto (opsional) -->
                                <div class="mb-4">
                                    <label class="form-label text-white" for="foto">Upload Foto</label>
                                    <input type="file" name="foto" id="foto" class="form-control form-control-lg"
                                        accept="image/*" />
                                </div>

                                <div class="d-flex justify-content-center mb-4">
                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Register</button>
                                </div>

                                <p class="mb-0 text-center">Already have an account? <a href="login.php"
                                        class="text-white-50 fw-bold">Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>