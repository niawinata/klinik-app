<?php
session_start();
include '../config/koneksi.php';

// Pastikan yang akses adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id_jadwal']) && isset($_GET['id_user'])) {
    $id_jadwal = $_GET['id_jadwal'];
    $id_user = $_GET['id_user'];

    // Hapus jadwal dokter terlebih dahulu
    mysqli_query($koneksi, "DELETE FROM jadwal_dokter WHERE id_jadwal = '$id_jadwal'");

    // Hapus data akun login dokter di tabel users
    mysqli_query($koneksi, "DELETE FROM users WHERE id_user = '$id_user'");

    echo "<script>alert('Data dokter berhasil dihapus!'); window.location='kelola_dokter.php';</script>";
} else {
    header("Location: kelola_dokter.php");
}
exit;
?>