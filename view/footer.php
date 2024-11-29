<style>
/* Custom Footer Styling */
.custom-footer-wrapper .custom-bg {
    /* Warna coklat tua */
    display: flex;
    justify-content: center;
    /* Tengah secara horizontal */
    padding: 20px 0;
}

.custom-footer-wrapper .row {
    max-width: 1100px;
    /* Lebar maksimum konten */
    width: 100%;
    /* Konten tetap responsif */
    display: flex;
    align-items: flex-start;
    /* Elemen sejajar dari atas */
}

.custom-footer-wrapper .col-lg-4 {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.custom-footer-wrapper h4 {
    margin-bottom: 15px;
    color: #fff;
}

.custom-footer-wrapper p {
    margin-bottom: 10px;
    color: #d9d9d9;
    /* Warna teks abu-abu terang */
}

.custom-footer-wrapper a i {
    color: #fff;
    /* Ikon berwarna putih */
    transition: color 0.3s ease;
}

.custom-footer-wrapper a i:hover {
    color: #f1c40f;
    /* Warna kuning pada hover */
}

.custom-footer-wrapper .text-light {
    color: #d9d9d9 !important;
    /* Sinkronisasi warna teks terang */
}

.custom-footer-wrapper .text-white {
    color: #fff !important;
}

.custom-footer-wrapper .fs-4 {
    font-size: 1.5rem;
    /* Ukuran ikon lebih besar */
}

.custom-footer-wrapper .me-2 {
    margin-right: 0.5rem;
}

.custom-footer-wrapper .me-3 {
    margin-right: 1rem;
}
</style>
<div class="container-fluid bg-dark mt-5 d-flex justify-content-center text-light custom-footer-wrapper">
    <div class="row text-center text-lg-start">
        <!-- Hotel Information -->
        <div class="col-lg-4 p-4">
            <h4 class="fw-bold">Hotel Grand Kenari</h4>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-4 p-4">
            <h4 class="fw-bold">Contact Us</h4>
            <p class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i>JL. BHARATA KAV. B46, PERUMNAS BUMI, Telukjambe
                Timur, Karawang, Jawa Barat 41371</p>
            <p class="mb-2"><i class="bi bi-telephone-fill me-2"></i>085219930189</p>
            <p><i class="bi bi-envelope-fill me-2"></i>info@yourdomain.com</p>
        </div>

        <!-- Social Media Links -->
        <div class="col-lg-4 p-4">
            <h4 class="fw-bold">Follow Us</h4>
            <a href="https://facebook.com" target="_blank" class="text-decoration-none">
                <i class="bi bi-facebook fs-4 me-3"></i>
            </a>
            <a href="https://twitter.com" target="_blank" class="text-decoration-none">
                <i class="bi bi-twitter fs-4 me-3"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="text-decoration-none">
                <i class="bi bi-instagram fs-4"></i>
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<h6 class="text-center custom-bg text-white p-3 m-0">Copyright © 2024 All Rights Reserved | Hotel Grand kenari by
    kelompok 16 | UBP Karawang</h6>