<?php
// Memulai session untuk mendeteksi status login admin
session_start();

// Menyertakan koneksi database, mundur satu folder untuk mencari file koneksi
include '../config/koneksi.php';

// KEAMANAN: Jika belum login atau bukan admin, usir ke halaman login utama
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ==========================================
// PROSES TAMBAH DOKTER BARU (JIKA TOMBOL DIKLIK)
// ==========================================
if (isset($_POST['tambah_dokter'])) {
    $username = $_POST['username'];
    // Enkripsi password dokter demi keamanan
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = 'dokter'; // Otomatis diset sebagai dokter

    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // 1. Masukkan data dokter ke dalam tabel 'users' terlebih dahulu
    $query_user = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama_lengkap', '$role')";
    
    if (mysqli_query($koneksi, $query_user)) {
        // Ambil ID user dokter yang baru saja terbuat otomatis
        $id_dokter_baru = mysqli_insert_id($koneksi);

        // 2. Masukkan jadwal dokter tersebut ke dalam tabel 'jadwal_dokter'
        $query_jadwal = "INSERT INTO jadwal_dokter (id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_dokter_baru', '$hari', '$jam_mulai', '$jam_selesai')";
        mysqli_query($koneksi, $query_jadwal);

        echo "<script>alert('Data dokter dan jadwal berhasil ditambahkan!'); window.location='kelola_dokter.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah dokter: " . mysqli_error($koneksi) . "');</script>";
    }
}

// ==========================================
// AMBIL SEMUA DATA DOKTER & JADWAL UNTUK DITAMPILKAN DI TABEL
// ==========================================
$query_tampil = "SELECT jd.id_jadwal, jd.id_dokter, u.nama_lengkap, jd.hari, jd.jam_mulai, jd.jam_selesai 
                 FROM jadwal_dokter jd
                 JOIN users u ON jd.id_dokter = u.id_user 
                 WHERE u.role = 'dokter'";
$result_tampil = mysqli_query($koneksi, $query_tampil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokter - KlinikMedika</title>
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
                    <a href="kelola_dokter.php" class="btn btn-primary fw-semibold">👨‍⚕️ Kelola Data Dokter</a>
                    <a href="konfirmasi_booking.php" class="btn btn-outline-success fw-semibold">📅 Konfirmasi Janji Temu</a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Form Tambah Dokter Baru (Sebelah Kiri) -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="card-title mb-0 fw-bold">Tambah Dokter & Jadwal</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap Dokter</label>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: dr. Setiawan, Sp.A" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Username Baru Dokter</label>
                                <input type="text" name="username" class="form-control" placeholder="Untuk login dokter" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password Akun Dokter</label>
                                <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hari Praktik</label>
                                <select name="hari" class="form-select" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>
                            <div class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" name="tambah_dokter" class="btn btn-primary w-100 fw-bold py-2">Simpan Dokter Baru</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Dokter yang Ada (Sebelah Kanan) -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 fw-bold text-secondary">Daftar Dokter & Jadwal Aktif</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-4">Nama Dokter</th>
                                        <th>Hari Praktik</th>
                                        <th>Jam Praktik</th>
                                        <th class="text-center">Aksi</th> <!-- Kolom Aksi Ditambahkan -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result_tampil) > 0) { ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_tampil)) { ?>
                                            <tr>
                                                <td class="px-4 fw-semibold"><?php echo $row['nama_lengkap']; ?></td>
                                                <td><span class="badge bg-info text-white"><?php echo $row['hari']; ?></span></td>
                                                <td><?php echo $row['jam_mulai'] . " - " . $row['jam_selesai']; ?> WIB</td>
                                                <td class="text-center">
                                                    <!-- Tombol Hapus dengan konfirmasi -->
                                                    <a href="hapus_dokter.php?id_jadwal=<?php echo $row['id_jadwal']; ?>&id_user=<?php echo $row['id_dokter']; ?>" 
                                                       class="btn btn-danger btn-sm fw-semibold" 
                                                       onclick="return confirm('Yakin ingin menghapus dokter ini?')">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">Belum ada data dokter yang diinput.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>