<?php
// Memulai session untuk mengecek siapa yang login
session_start();

// Menyertakan file koneksi database. Mundur satu folder ('../') untuk mencari folder config
include '../config/koneksi.php';

// KEAMANAN KETAT: Jika belum login atau yang login BUKAN admin, usir paksa ke halaman login utama
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ==========================================
// PROSES UPDATE STATUS & NO ANTREAN (JIKA TOMBOL DIKLIK ADMIN)
// ==========================================
if (isset($_POST['update_status'])) {
    $id_booking = $_POST['id_booking'];
    $no_antrean = $_POST['no_antrean'];
    $status = $_POST['status'];

    // Perintah SQL untuk memperbarui nomor antrean dan status pada data booking tertentu
    $query_update = "UPDATE booking SET no_antrean = '$no_antrean', status = '$status' WHERE id_booking = '$id_booking'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data janji temu pasien berhasil diperbarui!'); window.location='konfirmasi_booking.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// ==========================================
// AMBIL SEMUA DATA JANJI TEMU YANG MASUK DARI PASIEN
// ==========================================
// Kita JOIN tabel booking dengan tabel users (untuk mengambil nama pasien) 
// dan tabel jadwal_dokter -> users (untuk mengambil nama dokter)
$query_booking = "SELECT b.id_booking, b.tanggal_janji, b.keluhan, b.no_antrean, b.status,
                         u_pasien.nama_lengkap AS nama_pasien,
                         u_dokter.nama_lengkap AS nama_dokter
                  FROM booking b
                  JOIN users u_pasien ON b.id_pasien = u_pasien.id_user
                  JOIN jadwal_dokter jd ON b.id_jadwal = jd.id_jadwal
                  JOIN users u_dokter ON jd.id_dokter = u_dokter.id_user
                  ORDER BY b.id_booking DESC";

$result_booking = mysqli_query($koneksi, $query_booking);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Janji Temu - Admin</title>
    <!-- Memanggil Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar Atas Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard_admin.php">⚙️ Dashboard Admin</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Login Sebagai: <strong><?php echo $_SESSION['nama_lengkap']; ?></strong></span>
                <a href="../logout.php" class="btn btn-danger btn-sm fw-bold">Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <!-- Pilihan Menu Navigasi Admin -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="bg-white p-3 rounded shadow-sm d-flex gap-2">
                    <a href="dashboard_admin.php" class="btn btn-outline-dark fw-semibold">🏠 Beranda Admin</a>
                    <a href="kelola_dokter.php" class="btn btn-outline-primary fw-semibold">👨‍⚕️ Kelola Data Dokter</a>
                    <a href="konfirmasi_booking.php" class="btn btn-success fw-semibold">📅 Konfirmasi Janji Temu</a>
                </div>
            </div>
        </div>

        <!-- Tabel List Pendaftaran Janji Temu Pasien -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold text-secondary">Data Pengajuan Janji Temu Pasien</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Nama Pasien</th>
                                <th>Tanggal Periksa</th>
                                <th>Dokter Tujuan</th>
                                <th>Keluhan Pasien</th>
                                <th>No. Antrean</th>
                                <th>Status</th>
                                <th class="text-center">Aksi / Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result_booking) > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($result_booking)) { ?>
                                    <tr>
                                        <td class="px-4 fw-semibold"><?php echo $row['nama_pasien']; ?></td>
                                        <td><?php echo $row['tanggal_janji']; ?></td>
                                        <td><?php echo $row['nama_dokter']; ?></td>
                                        <td><small><?php echo $row['keluhan']; ?></small></td>
                                        
                                        <!-- Form aksi untuk mengupdate no antrean dan status per baris data -->
                                        <form action="" method="POST">
                                            <input type="hidden" name="id_booking" value="<?php echo $row['id_booking']; ?>">
                                            
                                            <td>
                                                <!-- Kolom Input No Antrean secara manual oleh admin -->
                                                <input type="number" name="no_antrean" class="form-control form-control-sm" style="width: 80px;" value="<?php echo $row['no_antrean']; ?>" placeholder="Belum">
                                            </td>
                                            <td>
                                                <!-- Pilihan dropdown status -->
                                                <select name="status" class="form-select form-select-sm">
                                                    <option value="Menunggu" <?php if($row['status'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                                                    <option value="Dikonfirmasi" <?php if($row['status'] == 'Dikonfirmasi') echo 'selected'; ?>>Dikonfirmasi</option>
                                                    <option value="Selesai" <?php if($row['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="submit" name="update_status" class="btn btn-warning btn-sm fw-bold px-3">Update</button>
                                            </td>
                                        </form>

                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Belum ada pasien yang mengirimkan pengajuan janji temu.</td>
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