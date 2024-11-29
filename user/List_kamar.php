<?php
session_start();
include '../backend/getkamar.php'; // Memasukkan data fasilitas dari database
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamar</title>
    <link rel="icon" href="../img/home/logo.jpg" type="image/png">
    <?php require('view/link.php'); ?>
    <link rel="stylesheet" href="css/kamar.css">
</head>

<body>

    <!-- Navbar -->
    <?php require('view/navbar.php'); ?>
    <!-- Navbar end -->

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Kamar yang Tersedia</h2>
        <hr>
    </div>

    <div class="rooms py-5">
        <div class="container">
            <div class="row g-4">

                <!-- Cek apakah ada kamar yang tersedia -->
                <?php if (!empty($kamar_data)): ?>
                <?php foreach ($kamar_data as $kamar): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="rooms_item">
                        <!-- Gambar Kamar -->
                        <div class="rooms_image">
                            <img src="../img/kamar/<?php echo htmlspecialchars($kamar['gambar']); ?>" alt="Room Image"
                                class="img-fluid">
                        </div>
                        <!-- Informasi Kamar -->
                        <div class="rooms_title_container text-center">
                            <div class="rooms_title h-font">
                                <h1><?php echo htmlspecialchars($kamar['tipe']); ?></h1>
                            </div>
                            <div class="rooms_price h-font">
                                <span>IDR <?php echo number_format($kamar['harga'], 0, ',', '.'); ?></span> / malam
                            </div>
                        </div>
                        <!-- Fasilitas -->
                        <div class="rooms_list h-font">
                            <div class="fasilitas-title h-font">Fasilitas:</div>
                            <ul class="list-unstyled mb-3 fasilitas-list">
                                <?php foreach ($kamar['fasilitas'] ?? [] as $fasilitas): ?>
                                <li class="d-flex flex-row align-items-center">
                                    <div><?php echo htmlspecialchars($fasilitas); ?></div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- Jumlah Kamar -->
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Tersedia:</span>
                                <span><?php echo htmlspecialchars($kamar['jumlah']); ?> kamar</span>
                            </div>
                        </div>
                        <!-- Tombol Pesan -->
                        <div class="button rooms_button text-center mt-3">
                            <a href="pesan.php?idkamar=<?php echo $kamar['idkamar']; ?>"
                                class="btn btn-primary">Pesan</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <!-- Pesan jika tidak ada kamar -->
                <div class="text-center">
                    <p>No rooms available.</p>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require('view/footer.php'); ?>
    <!-- Footer end -->

</body>

</html>