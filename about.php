<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <?php require('view/link.php');?>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cardo:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>

    <!-- navbar -->
    <?php require('view/navbar.php');?>
    <!-- navbar end -->

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Tentang Kami</h2>
        <hr>
        <p class="text-center mt-3">Sebagai anggota terbaru dari Bird’s Group, Hotel Grand Kenari hadir dengan konsep
            yang menyatukan kenyamanan modern dan efisiensi biaya. Terletak strategis di Jalan Bharata, Perumnas Bumi
            Telukjambe, Kecamatan Telukjambe Timur, hotel ini menawarkan akses mudah ke berbagai pusat bisnis dan
            industri di Karawang, menjadikannya pilihan ideal bagi para pekerja perusahaan, sales, dan pelancong yang
            mencari penginapan nyaman tanpa membebani kantong.

        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">GRAND KENARI</h3>
                <p> Meski merupakan hotel melati, Grand Kenari menghadirkan fasilitas kamar setara hotel berbintang.
                    Setiap kamar dirancang dengan perhatian pada detail, menawarkan tempat tidur berkualitas, fasilitas
                    mandi modern, dan suasana yang nyaman untuk memastikan tamu merasa seperti di rumah.
                    <br><br>
                    Salah satu keunggulan Grand Kenari adalah fokusnya pada kebutuhan pasar lokal. Dari pekerja
                    profesional hingga pelancong dengan anggaran terbatas, hotel ini menawarkan pengalaman menginap yang
                    istimewa, dengan layanan ramah dan berkelas khas Bird’s Group.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="img/home/1.jpeg" class="w-100 h-100">
            </div>
        </div>
    </div>
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="img/about/hotel.svg" width="70px">
                    <h4 class="mt-3">41+ Kamar</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="img/about/rating.svg" width="70px">
                    <h4 class="mt-3">244+ Review</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="img/about/staff.svg" width="70px">
                    <h4 class="mt-3">7 Staff</h4>
                </div>
            </div>
        </div>
        <div class="container">
            <iframe class="w-100 rounded mb-4" height="320px"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.443035545271!2d107.2787603!3d-6.336614300000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69772dce2ddda5%3A0x77080e8a6895ae8c!2sHotel%20Grand%20Kenari!5e0!3m2!1sid!2sid!4v1729866409908!5m2!1sid!2sid"
                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>


    <!-- Footer -->
    <?php require('view/footer.php');?>
    <!-- footer end -->

    <!-- js -->

</body>

</html>