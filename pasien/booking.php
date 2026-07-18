<?php
// Memulai session untuk mengambil data pasien yang sedang login
session_start();

// Menyertakan koneksi database
include '../config/koneksi.php';

// KEAMANAN KETAT: Jika belum login atau bukan pasien, tendang ke login utama
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pasien') {
    header("Location: ../login.php");
    exit;
}

$id_pasien = $_SESSION['id_user'];

// ==========================================
// PROSES SIMPAN JANJI TEMU (JIKA TOMBOL DIKLIK)
// ==========================================
if (isset($_POST['kirim_booking'])) {
    $id_jadwal = $_POST['id_jadwal'];
    $tanggal_janji = $_POST['tanggal_janji'];
    $keluhan = $_POST['keluhan'];
    $status = 'Menunggu'; // Status awal kiriman janji temu

    // Query untuk menyimpan data pendaftaran ke tabel booking
    $query_simpan = "INSERT INTO booking (id_pasien, id_jadwal, tanggal_janji, keluhan, status) 
                     VALUES ('$id_pasien', '$id_jadwal', '$tanggal_janji', '$keluhan', '$status')";

    if (mysqli_query($koneksi, $query_simpan)) {
        echo "<script>alert('Pendaftaran janji temu berhasil dikirim!'); window.location='dashboard_pasien.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim pendaftaran: " . mysqli_error($koneksi) . "');</script>";
    }
}

// ==========================================
// AMBIL DATA DOKTER & JADWAL UNTUK DROPDOWN PADA FORM
// ==========================================
$query_dokter = "SELECT jd.id_jadwal, u.nama_lengkap, jd.hari, jd.jam_mulai, jd.jam_selesai 
                 FROM jadwal_dokter jd
                 JOIN users u ON jd.id_dokter = u.id_user 
                 WHERE u.role = 'dokter'";
$result_dokter = mysqli_query($koneksi, $query_dokter);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Janji Temu - KlinikMedika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .card-booking {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #0d6efd, #00c6ff);
            color: white;
            border-top-left-radius: 16px !important;
            border-top-right-radius: 16px !important;
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .input-group-text {
            background-color: #f8f9fa;
            color: #0d6efd;
            border-right: none;
        }
        .form-control, .form-select {
            border-left: none;
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        .input-group:focus-within .input-group-text {
            border-color: #0d6efd;
        }
        .input-group:focus-within .form-control,
        .input-group:focus-within .form-select {
            border-color: #0d6efd;
        }
        .btn-submit {
            background: linear-gradient(135deg, #198754, #20c997);
            border: none;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-primary shadow-sm mb-5">
        <div class="container">
            <a class="navbar-brand" href="dashboard_pasien.php">🏥 KlinikMedika</a>
            <span class="navbar-text text-white">
                Halo, <strong class="text-warning"><?php echo $_SESSION['nama_lengkap']; ?></strong>
            </span>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                
                <a href="dashboard_pasien.php" class="btn btn-outline-secondary btn-sm mb-3 fw-medium">
                    <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>

                <div class="card card-booking">
                    <div class="card-header-custom text-center">
                        <h4 class="mb-1 fw-bold"><i class="fa-solid fa-calendar-check me-2"></i>Formulir Pendaftaran Janji Temu</h4>
                        <p class="mb-0 small opacity-75">Silakan lengkapi jadwal periksa dan keluhan Anda di bawah ini</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            
                            <div class="mb-4">
                                <label class="form-label">Pilih Dokter & Jadwal Praktik</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user-md"></i></span>
                                    <select name="id_jadwal" class="form-select" required>
                                        <option value="">-- Silakan Pilih Dokter --</option>
                                        <?php while ($row = mysqli_fetch_assoc($result_dokter)) { ?>
                                            <option value="<?php echo $row['id_jadwal']; ?>">
                                                <?php echo $row['nama_lengkap'] . " (" . $row['hari'] . " | " . substr($row['jam_mulai'], 0, 5) . " - " . substr($row['jam_selesai'], 0, 5) . " WIB)"; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Rencana Tanggal Periksa</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-calendar-day"></i></span>
                                    <input type="date" name="tanggal_janji" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Keluhan Singkat Penyakit</label>
                                <div class="input-group">
                                    <span class="input-group-text align-items-start pt-2"><i class="fa-solid fa-notes-medical"></i></span>
                                    <textarea name="keluhan" class="form-control" rows="4" placeholder="Tuliskan gejala atau keluhan sakit yang Anda rasakan saat ini secara singkat..." required></textarea>
                                </div>
                            </div>

                            <button type="submit" name="kirim_booking" class="btn btn-success btn-submit w-100 text-white shadow-sm mt-2">
                                <i class="fa-solid fa-paper-plane me-2"></i>Kirim Pengajuan Janji Temu
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>