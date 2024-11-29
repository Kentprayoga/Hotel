<?php
include('../config/db.php');
session_start();
$sql = "SELECT k.idkamar, k.tipe, k.jumlah, k.harga, k.gambar, f.nama_fasilitas 
        FROM kamar k
        LEFT JOIN kamar_fasilitas kf ON k.idkamar = kf.idkamar
        LEFT JOIN fasilitas f ON f.idfasilitas = kf.idfasilitas";

$stmt = $pdo->query($sql);

if ($stmt->rowCount() > 0) {
    $kamar_data = [];
    
    // Mengambil semua data kamar dan fasilitasnya
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $kamar_id = $row['idkamar'];
        
        // Jika kamar belum ada di array, buat entry baru untuk kamar tersebut
        if (!isset($kamar_data[$kamar_id])) {
            $kamar_data[$kamar_id] = [
                'idkamar' => $row['idkamar'],
                'tipe' => $row['tipe'],
                'jumlah' => $row['jumlah'],
                'harga' => $row['harga'],
                'gambar' => $row['gambar'],
                'fasilitas' => []
            ];
        }
        
        // Menambahkan fasilitas ke kamar yang sudah ada
        if ($row['nama_fasilitas']) {
            $kamar_data[$kamar_id]['fasilitas'][] = $row['nama_fasilitas'];
        }
    }
} else {
    echo "<p>No rooms available.</p>";
}

$sql = "SELECT r.review_text, r.rating, u.Foto, u.nama_lengkap
        FROM review r
        JOIN users u ON r.iduser = u.user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Ambil semua review
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jika tidak ada review
if ($stmt->rowCount() == 0) {
    echo '<p class="text-center">Belum ada review.</p>';
}

$sql = "SELECT * FROM fasilitas_umu";
$stmt = $pdo->query($sql);  // Menjalankan query untuk mengambil data
$facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Mengambil hasil query sebagai array asosiatif
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <link rel="icon" href="../img/home/logo.jpg" type="image/png">
    <!-- Bootstrap CSS (CDN) -->
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Custom CSS (local file) -->


    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/index.css">
</head>


<style>
body {
    background-color: aliceblue;
    font-family: 'Poppins', sans-serif;
}

/* Menargetkan hanya elemen dalam #fasilitas */
#fasilitas .col-lg-2,
#fasilitas .col-md-3,
#fasilitas .col-sm-4 {
    background-color: #f8f9fa;
    border: 2px solid #ddd;
    padding: 20px;
    transition: all 0.3s ease;
}

/* Efek hover pada kolom fasilitas dalam #fasilitas */
#fasilitas .col-lg-2:hover,
#fasilitas .col-md-3:hover,
#fasilitas .col-sm-4:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background-color: #e9ecef;
}

/* Efek pada gambar di dalam #fasilitas */
#fasilitas img {
    transition: transform 0.3s ease;
}

/* Efek hover pada gambar di dalam #fasilitas */
#fasilitas .col-lg-2:hover img,
#fasilitas .col-md-3:hover img,
#fasilitas .col-sm-4:hover img {
    transform: scale(1.1);
}

/* Menargetkan hanya elemen h5 dalam #fasilitas */
#fasilitas h5 {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-top: 15px;
}

/* Padding untuk tombol "More Fasilitas" */
#fasilitas .col-12.text-center {
    padding-bottom: 30px;
}

/* Styling gambar profil di dalam #fasilitas */
#fasilitas .profile img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    margin-right: 15px;
}

/* Styling untuk card review dalam #fasilitas */
#fasilitas .swiper-slide {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: transform 0.3s ease-in-out;
}

/* Efek hover pada card review dalam #fasilitas */
#fasilitas .swiper-slide:hover {
    transform: translateY(-5px);
}

/* Styling untuk teks review di dalam #fasilitas */
#fasilitas .swiper-slide p {
    font-size: 14px;
    line-height: 1.6;
    color: #333;
    margin-bottom: 10px;
}

/* Styling nama pengguna dalam #fasilitas */
#fasilitas .profile h6 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

/* Styling rating di dalam #fasilitas */
#fasilitas .rating i {
    font-size: 18px;
}

#fasilitas .bi-star-fill.text-warning {
    color: #f39c12;
}

/* Styling pagination swiper di dalam #fasilitas */
#fasilitas .swiper-pagination {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
}

#fasilitas .swiper-pagination-bullet {
    background-color: #007bff;
}

#fasilitas .swiper-pagination-bullet-active {
    background-color: #f39c12;
}

.profile img {
    width: 50px;
    /* Ukuran gambar yang sedang */
    height: 50px;
    object-fit: cover;
    /* Agar gambar tetap proporsional dan tidak terdistorsi */
    border-radius: 50%;
    /* Membuat gambar berbentuk lingkaran */
    margin-right: 15px;
    /* Memberikan jarak antara gambar dan nama */
}

/* Styling untuk card review */
.swiper-slide {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    /* Membuat sudut lebih lembut */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Menambahkan bayangan untuk efek card */
    margin-bottom: 20px;
    transition: transform 0.3s ease-in-out;
    /* Efek hover */
}

.swiper-slide:hover {
    transform: translateY(-5px);
    /* Efek hover card */
}

/* Styling untuk teks review */
.swiper-slide p {
    font-size: 14px;
    /* Ukuran teks sedang */
    line-height: 1.6;
    color: #333;
    margin-bottom: 10px;
    /* Jarak antar teks */
}

/* Styling untuk nama pengguna */
.profile h6 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

/* Styling untuk rating */
.rating i {
    font-size: 18px;
}

/* Membuat bintang berwarna kuning ketika rating diberikan */
.bi-star-fill.text-warning {
    color: #f39c12;
    /* Warna bintang */
}

.swiper-pagination {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
}

.swiper-pagination-bullet {
    background-color: #007bff;
}

.swiper-pagination-bullet-active {
    background-color: #f39c12;
}
</style>

<body>

    <!-- navbar -->
    <?php require('view/navbar.php');?>

    <!-- navbar end -->
    <!-- home -->
    <div id="home" class="container-fluid px-lg-4 mt-4">
        <div id="carouselHome" class="carousel slide" data-bs-ride="carousel">
            <!-- Indikator Carousel -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselHome" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <!-- Wrapper untuk Slide Gambar -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="image-container">
                        <img src="../img/home/1.jpeg" class="d-block w-100" alt="Slide 1" />
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="image-container">
                        <img src="../img/home/2.jpeg" class="d-block w-100" alt="Slide 2" />
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="image-container">
                        <img src="../img/home/3.jpeg" class="d-block w-100" alt="Slide 3" />
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="image-container">
                        <img src="../img/home/4.jpeg" class="d-block w-100" alt="Slide 4" />
                    </div>
                </div>
            </div>

            <!-- Kontrol Carousel (Previous & Next buttons) -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselHome" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselHome" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- home end -->
    <!-- Chek ketersediaan Kamar -->
    <div class="container availability-form">
        <div class="row justify-content-center">
            <div class="col-lg-10 bg-white shadow p-4 rounded">
                <h5 class="mb-4">Cek Ketersediaan Kamar</h5>
                <form id="form-cek-ketersediaan">
                    <div class="row align-items-end">
                        <!-- Input untuk Check-in -->
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Check-in</label>
                            <input type="date" class="form-control shadow-none" name="checkin" required>
                        </div>

                        <!-- Input untuk Check-out -->
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Check-out</label>
                            <input type="date" class="form-control shadow-none" name="checkout" required>
                        </div>

                        <!-- Pilihan Tipe Kamar -->
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Tipe Kamar</label>
                            <select class="form-select shadow-none" name="tipe_kamar" required>
                                <option value="" disabled selected>Tipe Kamar</option>
                                <option value="2">VVIP</option>
                                <option value="4">VIP</option>
                                <option value="5">Executive</option>
                                <option value="6">Suite</option>
                                <option value="7">Deluxe A</option>
                                <option value="8">Deluxe B</option>
                                <option value="9">Standar A</option>
                                <option value="10">Standar B</option>
                            </select>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="col-lg-3 mb-3">
                            <button type="button" class="btn text-white shadow-none custom-bg w-100"
                                id="submit-form">Submit</button>
                        </div>
                    </div>
                </form>

                <!-- Area untuk menampilkan hasil cek ketersediaan -->
                <div class="mt-4" id="hasil-ketersediaan">
                    <!-- Hasil cek ketersediaan kamar akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>









    <!-- check end -->
    <!-- kamar -->
    <!--  -->
    <div class="container justify-content-center my-5">
        <h2 class="mt-5 pt-4 mb-4 text-center fe-bold h-font">Kamar yang tersedia</h2>
        <div class="row justify-content-start">
            <?php foreach ($kamar_data as $kamar): ?>
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card h-100">
                    <!-- Menggunakan gambar dengan path relatif dan menambahkan data-toggle modal -->
                    <img src="../img/kamar/<?php echo $kamar['gambar']; ?>" class="card-img-top kamar-img"
                        alt="Kamar <?php echo htmlspecialchars($kamar['tipe']); ?>" data-bs-toggle="modal"
                        data-bs-target="#modal-<?php echo $kamar['idkamar']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($kamar['tipe']); ?></h5>
                        <h6 class="text-muted mb-4">IDR <?php echo number_format($kamar['harga'], 0, ',', '.'); ?> /
                            malam</h6>
                        <div class="features mb-2">
                            <h6 class="mb-1">Deskripsi Kamar</h6>
                            <p class="small text-muted">
                                Kamar ini tersedia dengan fasilitas lengkap, sangat cocok untuk kebutuhan menginap Anda.
                            </p>
                        </div>
                        <div class="fasilitas mb-2">
                            <h6 class="mb-1">Fasilitas Kamar</h6>
                            <ul class="list-unstyled mb-3">
                                <?php foreach ($kamar['fasilitas'] as $fasilitas): ?>
                                <li class="small text-muted"><?php echo htmlspecialchars($fasilitas); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-between">
                            <!-- Menambahkan link dengan parameter idkamar -->
                            <a href="Detail_kamar.php?idkamar=<?php echo urlencode($kamar['idkamar']); ?>"
                                class="btn btn-sm btn-outline-dark shadow-none">More Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk melihat gambar lebih besar -->
            <div class="modal fade" id="modal-<?php echo $kamar['idkamar']; ?>" tabindex="-1"
                aria-labelledby="modalLabel-<?php echo $kamar['idkamar']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel-<?php echo $kamar['idkamar']; ?>">Gambar Kamar
                                <?php echo htmlspecialchars($kamar['tipe']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="../img/kamar/<?php echo $kamar['gambar']; ?>" class="img-fluid"
                                alt="Kamar <?php echo htmlspecialchars($kamar['tipe']); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>


    <!--  -->

    <!-- kamar end -->
    <!-- Fasilitas -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Fasilitas Hotel</h2>

    <div id="fasilitas" class="container justify-content-center">
        <!-- Gunakan row dengan kelas d-flex untuk menata kolom secara horizontal di tengah -->
        <div class="row justify-content-start">
            <!-- Membungkus fasilitas dalam row untuk memastikan layout grid bekerja -->
            <!-- Gunakan foreach untuk menampilkan setiap fasilitas -->
            <?php foreach ($facilities as $facility): ?>
            <div class="col-lg-2 col-md-3 col-sm-4 text-center bg-white rounded shadow py-4 my-3 mx-2">
                <!-- Menampilkan gambar fasilitas -->
                <img src="./../img/fasilitas/<?= $facility['gambar'] ?>" width="80px"
                    alt="<?= $facility['nama_fasilitas'] ?>">

                <!-- Menampilkan nama fasilitas -->
                <h5 class="mt-3"><?= htmlspecialchars($facility['nama_fasilitas']) ?></h5>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-12 text-center mt-5">
        <a href="fasilitas.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none h-font">More
            Fasilitas</a>
    </div>
    <!-- Fasilitas end -->
    <!-- review -->
    <!-- review -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Review</h2>
    <div class="container">
        <div id="carouselReview" class="carousel slide" data-bs-ride="carousel">
            <!-- Indikator Carousel -->
            <div class="carousel-indicators">
                <?php foreach ($reviews as $index => $review): ?>
                <button type="button" data-bs-target="#carouselReview" data-bs-slide-to="<?php echo $index; ?>"
                    class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="true"
                    aria-label="Slide <?php echo $index + 1; ?>"></button>
                <?php endforeach; ?>
            </div>

            <!-- Wrapper untuk Slide Review -->
            <div class="carousel-inner">
                <?php foreach ($reviews as $index => $review): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> bg-custom p-4">
                    <div class="profile d-flex align-items-center mb-4">
                        <!-- Menampilkan foto pengguna -->
                        <?php if (!empty($review['Foto']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/final_project/img/upload/' . $review['Foto'])): ?>
                        <img src="../img/upload/<?php echo htmlspecialchars($review['Foto']); ?>" alt="Foto Pengguna"
                            width="50" height="50" class="rounded-circle">
                        <?php else: ?>
                        <img src="img/default-avatar.png" alt="Foto Default" width="50" height="50"
                            class="rounded-circle">
                        <?php endif; ?>
                        <h6 class="m-0 ms-2"><?php echo htmlspecialchars($review['nama_lengkap']); ?></h6>
                    </div>
                    <p>
                        <?php echo nl2br(htmlspecialchars($review['review_text'])); ?>
                    </p>
                    <div class="rating">
                        <?php
                        // Menampilkan bintang berdasarkan rating
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $review['rating']) {
                                echo '<i class="bi bi-star-fill text-warning"></i>';
                            } else {
                                echo '<i class="bi bi-star-fill text-muted"></i>';
                            }
                        }
                    ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Tombol Navigasi -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselReview" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselReview" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

            <!-- Pagination (optional, based on your design needs) -->
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- review end -->
    <!-- Footer -->
    <?php require('view/footer.php');?>
    <!-- footer end -->
    <!-- js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $("#submit-form").click(function(e) {
        e.preventDefault();

        // Ambil data dari form
        var formData = $("#form-cek-ketersediaan").serialize();

        // Kirim data form via AJAX
        $.ajax({
            url: 'proses-cek-ketersediaan.php', // File PHP untuk memproses
            type: 'POST',
            data: formData,
            success: function(response) {
                // Menampilkan hasil dari server
                $("#hasil-ketersediaan").html(response);
            },
            error: function() {
                // Menampilkan pesan error jika terjadi masalah
                $("#hasil-ketersediaan").html(
                    "<div class='alert alert-danger'>Terjadi kesalahan. Coba lagi.</div>");
            }
        });
    });
    </script>

</body>

</html>