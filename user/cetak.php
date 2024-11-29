<?php
// Mengimpor fpdf.php dari vendor
require('../lib/fpdf.php');  // Sesuaikan path relatif jika perlu
require('../config/db.php');

// Koneksi ke database menggunakan PDO
// Ambil idpesan dari URL
$idpesan = isset($_GET['id']) ? $_GET['id'] : 0; // Jika id tidak ada, set default 0

if ($idpesan == 0) {
    die('ID Pesanan tidak valid.');
}

// Query untuk mengambil data pemesanan berdasarkan idpesan
$sql = "SELECT p.idpesan, k.tipe, u.nama_lengkap, p.tglpesan, p.tglmasuk, p.tglkeluar, p.totalbayar, p.status 
        FROM pemesanan p
        JOIN kamar k ON p.idkamar = k.idkamar
        JOIN users u ON p.idtamu = u.user_id
        WHERE p.idpesan = :idpesan";  // Menggunakan prepared statement dengan parameter

// Menyiapkan dan mengeksekusi query
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':idpesan', $idpesan, PDO::PARAM_INT);
$stmt->execute();

// Jika data pemesanan tidak ditemukan
if ($stmt->rowCount() == 0) {
    die('Data pemesanan tidak ditemukan.');
}

// Mengambil data pemesanan
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Membuat instance objek FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Menambahkan logo hotel (sesuaikan path dengan lokasi logo Anda)
$logoPath = '../img/home/logo.jpg'; // Ganti dengan path logo Anda
$pdf->Image($logoPath, 10, 8, 30); // Ukuran logo lebih besar (Lebar 30 mm)

// Menambahkan nama hotel dengan warna
// Menambahkan nama hotel dengan warna
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(0, 102, 204);  // Menetapkan warna biru untuk nama hotel
$pdf->Cell(0, 10, 'Hotel Grand Kenari', 0, 1, 'C');  // Nama hotel di tengah

// Memberikan jarak lebih banyak sebelum menggambar garis
$pdf->Ln(15);  // Menambahkan jarak vertikal lebih banyak (15 mm) setelah nama hotel

// Menambahkan garis pemisah
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Garis horizontal di bawah nama hotel


// Menambahkan judul bukti pembayaran
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 0, 0);  // Mengembalikan warna teks menjadi hitam
$pdf->Cell(200, 10, 'Bukti Pembayaran', 0, 1, 'C');

// Memberikan jarak antara judul dan konten
$pdf->Ln(10);

// Menampilkan informasi pemesanan dalam format tabel untuk penataan yang lebih baik
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'ID Pesanan', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['idpesan'], 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Tipe Kamar', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['tipe'], 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nama ', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['nama_lengkap'], 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Tanggal Pesan', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date('d-m-Y', strtotime($row['tglpesan'])), 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Tanggal Masuk', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date('d-m-Y', strtotime($row['tglmasuk'])), 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Tanggal Keluar', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date('d-m-Y', strtotime($row['tglkeluar'])), 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Total Bayar', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Rp. ' . number_format($row['totalbayar'], 0, ',', '.'), 1, 1, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Status', 1, 0, 'L', false);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['status'], 1, 1, 'L');

// Menambahkan garis pemisah
$pdf->Line(10, $pdf->GetY() + 5, 200, $pdf->GetY() + 5); // Garis horizontal di bawah informasi pembayaran

// Menambahkan catatan atau footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Terima kasih telah memilih Hotel Grand Kenari. Kami berharap Anda puas dengan layanan kami.', 0, 1, 'C');

// Menyimpan atau mengeluarkan file PDF
$pdf->Output('I', 'Bukti_Pembayaran_' . $idpesan . '.pdf');

// Menutup koneksi database
$pdo = null;
?>