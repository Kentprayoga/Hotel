<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kamar</title>
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
        <h2 class="fw-bold h-font text-center">Kamar yang tersedia</h2>
        <hr>
    </div>
    <div class="container">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4">
                <nav class="navbar navbar-expand-lg bg-body-tertiary rounde shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">Filter</h4>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">
                                    CHECK AVAILABILITY
                                </h5>
                                <label class="form-label">check-in</label>
                                <input type="date" class="form-control shadow-none mb-3">
                                <label class="form-label">check-out</label>
                                <input type="date" class="form-control shadow-none ">
                            </div>
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">
                                    FASILITAS
                                </h5>
                                <div class="mb-2">
                                    <input type="checkbox" id="f1" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f1">ac</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f2" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f2">Tv</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f3" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f3">single bed</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f4" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f4">double bed</label>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" id="f5" class="form-check-input shadow-none me-1">
                                    <label class="form-check-label" for="f5">Sarapan</label>
                                </div>
                            </div>
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="mb-3" style="font-size: 18px;">
                                    Tamu
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">dewasa</label>
                                        <input type="number" class="form-control shadow-none ">
                                    </div>
                                    <div>
                                        <label class="form-label">anak anak</label>
                                        <input type="number" class="form-control shadow-none ">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow">
                    <div class="row g-0 p-3 align-items-center">
                        <div class="col-md-5">
                            <img src="img/kamar/3.jpeg" class="img-fluid rounded">
                        </div>
                        <div class="col-md-5">
                            <h5 class="mb-1">Vvip</h5>
                        </div>
                        <div class="col-md-2">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural
                                    lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-body-secondary">Last updated 3 mins ago</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Footer -->
    <?php require('view/footer.php');?>
    <!-- footer end -->

    <!-- js -->

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    var swiper = new Swiper(".swiper-container", {
        spaceBetween: 30,
        effect: "fade",
        loop: true,
        autoplay: {
            delay: 2000,
            diableOnInteraction: false,
        }
    });
    var swiper = new Swiper(".swiper-review", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: true,
        },
        pagination: {
            el: ".swiper-pagination",
        },
    });
    </script>
</body>

</html>