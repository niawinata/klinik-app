<?php
// Memulai session untuk mendeteksi siapa yang login
session_start();

// Menyertakan file koneksi database. Mundur satu folder ('../') untuk mencari folder config
include '../config/koneksi.php';

// KEAMANAN KETAT: Jika belum login atau yang login BUKAN admin, tendang langsung ke halaman login utama
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// =======================================================
// MENGHITUNG TOTAL DATA SECARA OTOMATIS DARI DATABASE
// =======================================================

// Hitung berapa banyak pasien yang terdaftar (role = pasien)
$hitung_pasien = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role = 'pasien'");
$data_pasien   = mysqli_fetch_assoc($hitung_pasien);

// Hitung berapa banyak dokter yang terdaftar (role = dokter)
$hitung_dokter = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users WHERE role = 'dokter'");
$data_dokter   = mysqli_fetch_assoc($hitung_dokter);

// Hitung berapa total transaksi janji temu yang masuk
$hitung_booking = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM booking");
$data_booking   = mysqli_fetch_assoc($hitung_booking);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - KlinikMedika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">⚙️ Dashboard Admin</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Login Sebagai: <strong><?php echo $_SESSION['nama_lengkap']; ?></strong></span>
                <a href="../logout.php" class="btn btn-danger btn-sm fw-bold">Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="bg-white p-3 rounded shadow-sm d-flex gap-2">
                    <a href="dashboard_admin.php" class="btn btn-dark fw-semibold">🏠 Beranda Admin</a>
                    <a href="kelola_dokter.php" class="btn btn-outline-primary fw-semibold">👨‍⚕️ Kelola Data Dokter</a>
                    <a href="konfirmasi_booking.php" class="btn btn-outline-success fw-semibold">📅 Konfirmasi Janji Temu</a>
                </div>
            </div>
        </div>

        <h4 class="fw-bold text-secondary mb-4">Selamat Datang di Panel Kendali KlinikMedika</h4>

        <div class="row g-3">
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white p-3">
                    <div class="card-body">
                        <h6 class="text-uppercase fw-bold opacity-75">Total Pasien Terdaftar</h6>
                        <h2 class="fw-bold mb-0"><?php echo $data_pasien['total']; ?> Orang</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white p-3">
                    <div class="card-body">
                        <h6 class="text-uppercase fw-bold opacity-75">Total Dokter Aktif</h6>
                        <h2 class="fw-bold mb-0"><?php echo $data_dokter['total']; ?> Orang</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark p-3">
                    <div class="card-body">
                        <h6 class="text-uppercase fw-bold opacity-75">Total Janji Temu Pasien</h6>
                        <h2 class="fw-bold mb-0"><?php echo $data_booking['total']; ?> Data</h2>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>