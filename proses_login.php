<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password']; 

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Memeriksa password: 
        // 1. $password === $row['password'] (untuk password teks biasa/angka 316)
        // 2. password_verify($password, $row['password']) (untuk password yang sudah di-hash)
        if ($password === $row['password'] || password_verify($password, $row['password'])) {
            
            $_SESSION['id_user']      = $row['id_user'];
            $_SESSION['username']     = $row['username'];
            $_SESSION['nama_lengkap']  = $row['nama_lengkap'];
            $_SESSION['role']         = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: admin/dashboard_admin.php");
            } elseif ($row['role'] === 'pasien') {
                header("Location: pasien/dashboard_pasien.php");
            } elseif ($row['role'] === 'dokter') {
                header("Location: dokter/dashboard_dokter.php");
            }
            exit;
        } else {
            header("Location: login.php?pesan=password_salah");
            exit;
        }
    } else {
        header("Location: login.php?pesan=username_tidak_ditemukan");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>