<?php
// Menyertakan file koneksi agar script ini bisa terhubung ke database MySQL
include 'config/koneksi.php';

// Memeriksa apakah user sudah menekan tombol dengan nama 'daftar'
if (isset($_POST['daftar'])) {
    
    // Mengambil data yang diketik user di dalam form input
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    
    // Mengamankan password dengan cara di-enkripsi (diubah jadi kode acak) sebelum disimpan ke DB
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    
    // Menentukan hak akses otomatis sebagai 'pasien' karena ini halaman daftar mandiri
    $role = 'pasien'; 

    // Perintah SQL untuk memasukkan data baru ke dalam tabel 'users'
    $query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama_lengkap', '$role')";
    
    // Menjalankan perintah SQL di atas
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, munculkan pesan sukses dan pindahkan user ke halaman login.php
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        // Jika gagal (misal koneksi terputus), munculkan pesan error dari MySQL
        echo "<script>alert('Registrasi gagal: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Informasi Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            /* MENGUBAH GAMBAR: Disamakan dengan halaman login agar satu tema medis yang aesthetic */
            background-image: url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
            /* Agar form selalu berada tepat di tengah-tengah layar */
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            margin: 0;
        }
        
        .card-regis { 
            width: 100%; 
            max-width: 450px; 
            border: 1px solid rgba(255, 255, 255, 0.25); /* Garis tepi tipis transparan */
            border-radius: 15px; /* Sudut kotak membulat */
            
            /* EFEK TRANSPARAN KACA (Glassmorphism):
               Latar belakang putih transparan tipis + efek blur pada gambar medis di belakangnya */
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2); 
        }

        /* Mengatur warna label teks input menjadi putih agar terbaca jelas di background gelap */
        .form-label {
            color: #ffffff;
            font-size: 0.9rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5); /* Efek bayangan teks */
        }

        /* Mengatur kolom inputan */
        .form-control {
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 8px;
        }

        /* Tombol daftar menggunakan warna hijau medis/klinik cerah */
        .btn-daftar {
            background-color: #198754;
            border: none;
            color: white;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-daftar:hover {
            background-color: #157347;
            color: white;
        }
    </style>
</head>
<body>

    <div class="card card-regis p-4">
        <div class="card-body">
            <h3 class="text-white fw-bold mb-1 text-center" style="letter-spacing: 1px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">🏥 Pendaftaran Pasien</h3>
            <p class="text-white-50 small mb-4 text-center" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Silakan lengkapi data diri Anda</p>

            <form action="" method="POST">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control py-2" placeholder="Nama sesuai KTP" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username Baru</label>
                    <input type="text" name="username" class="form-control py-2" placeholder="Buat username tanpa spasi" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="Buat password Anda" required>
                </div>

                <button type="submit" name="daftar" class="btn btn-daftar w-100 fw-bold py-2 mb-3">Daftar Akun</button>
            </form>

            <p class="text-center mb-0 small text-white-50" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                Sudah punya akun? <a href="login.php" class="text-white fw-semibold text-decoration-none">Login di sini</a>
            </p>
        </div>
    </div>

</body>
</html>