<?php
// Konfigurasi database
$host     = "localhost";
$username = "root";
$password = ""; // Secara default XAMPP dikosongkan
$database = "db_klinik"; // Sesuaikan dengan nama database yang kamu buat tadi

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>