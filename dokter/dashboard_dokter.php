<?php
session_start();
include '../config/koneksi.php';

// KEAMANAN KETAT: Jika bukan dokter, tendang keluar
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../login.php");
    exit;
}

$id_dokter = $_SESSION['id_user'];
$nama_dokter = $_SESSION['nama_lengkap'];

// Ambil pasien yang dijadwalkan dengan dokter ini dan berstatus 'Dikonfirmasi'
$query_pasien = "SELECT b.id_booking, b.tanggal_janji, b.keluhan, b.no_antrean, u_pas.nama_lengkap AS nama_pasien
                 FROM booking b
                 JOIN jadwal_dokter jd ON b.id_jadwal = jd.id_jadwal
                 JOIN users u_pas ON b.id_pasien = u_pas.id_user
                 WHERE jd.id_dokter = '$id_dokter' AND b.status = 'Dikonfirmasi'
                 ORDER BY b.no_antrean ASC";
$result_pasien = mysqli_query($koneksi, $query_pasien);
$total_pasien = mysqli_num_rows($result_pasien);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - KlinikMedika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
        .navbar-custom { background: linear-gradient(135deg, #00c6ff, #0072ff); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-user-doctor me-2"></i>Panel Dokter</a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white">Dokter: <strong class="text-warning"><?php echo $nama_dokter; ?></strong></span>
                <a href="../logout.php" class="btn btn-light btn-sm text-danger fw-semibold"><i class="fa-solid fa-right-from-bracket me-1"></i>Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1">Daftar Antrean Pasien Hari Ini</h3>
            <p class="text-muted">Silakan lakukan pemeriksaan berdasarkan nomor urut antrean pasien</p>
        </div>

        <div class="card card-custom overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4 text-center" style="width: 120px;">No. Antrean</th>
                                <th>Nama Pasien</th>
                                <th>Tanggal Periksa</th>
                                <th>Keluhan Gejala</th>
                                <th class="text-center" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_pasien > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($result_pasien)) { ?>
                                    <tr>
                                        <td class="text-center px-4">
                                            <span class="badge bg-primary fs-6 rounded-circle px-3 py-2"><?php echo $row['no_antrean']; ?></span>
                                        </td>
                                        <td class="fw-bold text-dark"><?php echo $row['nama_pasien']; ?></td>
                                        <td class="text-muted"><i class="fa-regular fa-calendar me-2"></i><?php echo $row['tanggal_janji']; ?></td>
                                        <td class="text-secondary"><?php echo $row['keluhan']; ?></td>
                                        <td class="text-center">
                                            <a href="periksa_pasien.php?id=<?php echo $row['id_booking']; ?>" class="btn btn-success btn-sm fw-semibold px-3 rounded-pill">
                                                <i class="fa-solid fa-stethoscope me-1"></i>Periksa
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-notes-medical fa-2xl d-block mb-3 text-opacity-50"></i>
                                        Tidak ada pasien dalam daftar antrean aktif saat ini.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>