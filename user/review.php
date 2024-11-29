<?php
// Menghubungkan ke database
require('../config/db.php');

// Memastikan ID Pesan diterima dari URL
$idpesan = isset($_GET['id']) ? $_GET['id'] : 0;
if ($idpesan == 0) {
    die('ID Pesanan tidak valid.');
}

// Cek apakah user sudah memberikan review untuk pesanan ini
session_start();
$user_id = $_SESSION['user_id'];  // Asumsi user_id disimpan dalam session setelah login

// Ambil data pesanan berdasarkan idpesan
$sql = "SELECT p.idpesan, p.tglpesan, k.tipe, u.nama_lengkap
        FROM pemesanan p
        JOIN kamar k ON p.idkamar = k.idkamar
        JOIN users u ON p.idtamu = u.user_id
        WHERE p.idpesan = :idpesan AND p.idtamu = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Jika pesanan tidak ditemukan
if ($stmt->rowCount() == 0) {
    die('Pesanan tidak ditemukan atau Anda tidak memiliki akses untuk memberikan review.');
}

$pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah user sudah memberikan review untuk pesanan ini
$sql_check = "SELECT * FROM review WHERE idpesan = :idpesan AND iduser = :user_id";
$stmt_check = $pdo->prepare($sql_check);
$stmt_check->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
$stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_check->execute();

// Jika user sudah memberikan review, tampilkan notifikasi
$review_exists = $stmt_check->rowCount() > 0;

// Proses form submission jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$review_exists) {
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    // Simpan review ke database
    $sql_insert = "INSERT INTO review (iduser, idpesan, review_text, rating) 
                   VALUES (:iduser, :idpesan, :review_text, :rating)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->bindParam(':iduser', $user_id, PDO::PARAM_INT);
    $stmt_insert->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
    $stmt_insert->bindParam(':review_text', $review_text, PDO::PARAM_STR);
    $stmt_insert->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt_insert->execute();
    
    // Flag sukses untuk JavaScript
    echo '<script>var reviewSubmitted = true;</script>';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Pesanan Anda</title>
    <?php require('view/link.php'); ?>
    <style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        margin-top: 10px;
    }

    .rating input {
        display: none;
    }

    .rating label {
        font-size: 40px;
        color: gray;
        cursor: pointer;
    }

    .rating input:checked~label,
    .rating label:hover,
    .rating label:hover~label {
        color: gold;
    }

    .form-container {
        max-width: 600px;
        margin: auto;
        margin-top: 50px;
    }

    textarea {
        resize: none;
    }

    /* Styling untuk Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        text-align: center;
    }

    .modal-header {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
    }

    .modal-body {
        margin-top: 20px;
        font-size: 18px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <?php require('view/navbar.php'); ?>
    <div class="container form-container">
        <h2 class="text-center">Review Pesanan Anda</h2>

        <p><strong>Pesanan ID:</strong> <?php echo $pesanan['idpesan']; ?></p>
        <p><strong>Nama Pengguna:</strong> <?php echo $pesanan['nama_lengkap']; ?></p>
        <p><strong>Tipe Kamar:</strong> <?php echo $pesanan['tipe']; ?></p>
        <p><strong>Tanggal Pesanan:</strong> <?php echo date('d-m-Y', strtotime($pesanan['tglpesan'])); ?></p>

        <?php if ($review_exists): ?>
        <div class="alert alert-info" role="alert">
            Anda sudah memberikan review untuk pesanan ini.
        </div>
        <?php else: ?>
        <form id="reviewForm" action="review.php?id=<?php echo $idpesan; ?>" method="POST">
            <div class="form-group">
                <label for="rating" class="text-center">Beri Rating Anda:</label>
                <div class="rating">
                    <input type="radio" name="rating" id="rating5" value="5" required>
                    <label for="rating5">★</label>
                    <input type="radio" name="rating" id="rating4" value="4">
                    <label for="rating4">★</label>
                    <input type="radio" name="rating" id="rating3" value="3">
                    <label for="rating3">★</label>
                    <input type="radio" name="rating" id="rating2" value="2">
                    <label for="rating2">★</label>
                    <input type="radio" name="rating" id="rating1" value="1">
                    <label for="rating1">★</label>
                </div>
            </div>

            <div class="form-group">
                <label for="review_text">Tulis Review Anda:</label>
                <textarea name="review_text" id="review_text" class="form-control" rows="4"
                    placeholder="Bagikan pengalaman Anda..." required></textarea>
            </div>
            <br>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Kirim Review</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <!-- Modal untuk notifikasi sukses -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Review Berhasil Dikirim</h2>
            </div>
            <div class="modal-body">
                <p>Review Anda telah berhasil disubmit!</p>
            </div>
        </div>
    </div>

    <?php require('view/footer.php'); ?>

    <script>
    // Menampilkan modal setelah form berhasil disubmit
    function showModal() {
        document.getElementById('successModal').style.display = 'block';
    }

    // Menutup modal
    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }

    // Menangani submit form dan menampilkan modal
    document.getElementById('reviewForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Menghentikan pengiriman form biasa
        // Lakukan pengiriman data ke server menggunakan fetch (AJAX)
        const formData = new FormData(this);
        fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
            .then(data => {
                // Jika sukses, tampilkan modal dan refresh halaman setelah 3 detik
                showModal();
                setTimeout(() => {
                    window.location.reload(); // Refresh halaman
                }, 3000); // Delay 3 detik
            });
    });
    </script>
</body>

</html>

<?php
// Menutup koneksi database
$pdo = null;
?>