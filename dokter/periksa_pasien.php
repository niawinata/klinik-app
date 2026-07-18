<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../login.php");
    exit;
}

$id_booking = $_GET['id'];

// Ambil data pasien yang akan diperiksa
$query_detail = "SELECT b.id_booking, b.keluhan, u_pas.nama_lengkap AS nama_pasien 
                 FROM booking b
                 JOIN users u_pas ON b.id_pasien = u_pas.id_user 
                 WHERE b.id_booking = '$id_booking'";
$result_detail = mysqli_query($koneksi, $query_detail);
$pasien = mysqli_fetch_assoc($result_detail);

// JIKA TOMBOL SIMPAN HASIL PERIKSA DIKLIK
if (isset($_POST['simpan_periksa'])) {
    $diagnosis = $_POST['diagnosis'];
    $resep_obat = $_POST['resep_obat'];

    // Update data booking: isi diagnosis, resep, dan ubah status menjadi 'Selesai'
    $query_update = "UPDATE booking SET 
                     diagnosis = '$diagnosis', 
                     resep_obat = '$resep_obat', 
                     status = 'Selesai' 
                     WHERE id_booking = '$id_booking'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Pemeriksaan selesai dicatat!'); window.location='dashboard_dokter.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan resep: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Rekam Medis - KlinikMedika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <a href="dashboard_dokter.php" class="btn btn-outline-secondary btn-sm mb-3 fw-medium"><i class="fa-solid fa-arrow-left me-2"></i>Kembali</a>
                
                <div class="card card-custom">
                    <div class="card-header bg-success text-white py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-notes-medical me-2"></i>Formulir Rekam Medis Pasien</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4 bg-light p-3 rounded">
                            <small class="text-muted d-block">Nama Pasien:</small>
                            <strong class="fs-5 text-dark"><?php echo $pasien['nama_pasien']; ?></strong>
                            <small class="text-muted d-block mt-2">Keluhan Awal Pasien:</small>
                            <span class="text-secondary">"<?php echo $pasien['keluhan']; ?>"</span>
                        </div>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Diagnosis Penyakit</label>
                                <textarea name="diagnosis" class="form-control" rows="3" placeholder="Contoh: Infeksi Saluran Pernapasan Akut (ISPA)" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Resep Obat / Tindakan Medis</label>
                                <textarea name="resep_obat" class="form-control" rows="4" placeholder="Contoh: Amoxicillin 3x1 tablet, Paracetamol 3x1 (jika demam)" required></textarea>
                            </div>
                            <button type="submit" name="simpan_periksa" class="btn btn-success w-100 fw-bold py-2 mt-2">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Rekam Medis & Selesai
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>