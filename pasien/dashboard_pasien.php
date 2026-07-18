<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pasien') {
    header("Location: ../login.php");
    exit;
}

$id_pasien = $_SESSION['id_user'];
$nama_pasien = $_SESSION['nama_lengkap'];

// Query terbaru yang mengambil diagnosis dan resep_obat
$query_riwayat = "SELECT b.tanggal_janji, b.keluhan, b.no_antrean, b.status, b.diagnosis, b.resep_obat, u_dok.nama_lengkap AS nama_dokter
                  FROM booking b
                  JOIN jadwal_dokter jd ON b.id_jadwal = jd.id_jadwal
                  JOIN users u_dok ON jd.id_dokter = u_dok.id_user
                  WHERE b.id_pasien = '$id_pasien'
                  ORDER BY b.id_booking DESC";
$result_riwayat = mysqli_query($koneksi, $query_riwayat);
$total_kunjungan = mysqli_num_rows($result_riwayat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pasien - KlinikMedika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f4f8; }
        .card-table { border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.04); }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-primary mb-4 py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-heart-pulse me-2"></i>KlinikMedika</a>
            <a href="../logout.php" class="btn btn-light btn-sm text-danger fw-bold">Keluar</a>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <h3>Riwayat Kunjungan Medis</h3>
            <a href="booking.php" class="btn btn-success">+ Buat Janji Temu</a>
        </div>

        <div class="card card-table">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Dokter</th>
                        <th>Keluhan</th>
                        <th>Antrean</th>
                        <th>Hasil Periksa (DP & ROP)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_riwayat)) { ?>
                    <tr>
                        <td><?php echo $row['tanggal_janji']; ?></td>
                        <td class="fw-bold"><?php echo $row['nama_dokter']; ?></td>
                        <td class="text-muted"><?php echo $row['keluhan']; ?></td>
                        <td class="text-center">
                            <span class="badge bg-dark rounded-circle"><?php echo $row['no_antrean'] ?: '-'; ?></span>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'Selesai') { ?>
                                <div class="small text-primary">
                                    <strong>DP:</strong> <?php echo $row['diagnosis']; ?><br>
                                    <strong>ROP:</strong> <?php echo $row['resep_obat']; ?>
                                </div>
                            <?php } else { ?>
                                <span class="text-muted small">Belum diperiksa</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php 
                            if ($row['status'] == 'Menunggu') echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                            elseif ($row['status'] == 'Dikonfirmasi') echo '<span class="badge bg-success">Dikonfirmasi</span>';
                            else echo '<span class="badge bg-secondary">Selesai</span>';
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>