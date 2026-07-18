<?php
// Memulai session agar bisa menghapusnya
session_start();

// Menghapus semua data session yang tersimpan
session_unset();
session_destroy();

// Mengarahkan kembali pengguna ke halaman login utama
header("Location: login.php");
exit;