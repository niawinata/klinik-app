<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            /* MENGUBAH GAMBAR: Sekarang menggunakan gambar interior klinik/medis yang aesthetic dari internet */
            background-image: url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
            /* Agar kotak login selalu berada tepat di tengah-tengah layar */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .card-login {
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.25); /* Garis tepi tipis transparan */
            border-radius: 15px; /* Sudut kotak membulat rapi */
            
            /* EFEK TRANSPARAN KACA (Glassmorphism):
               Latar belakang putih transparan tipis + efek blur pada gambar medis di belakangnya */
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2); /* Bayangan lembut dibawah kotak */
        }

        /* Mengatur warna label teks input menjadi putih agar terbaca jelas */
        .form-label {
            color: #ffffff;
            font-size: 0.9rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5); /* Efek bayangan teks agar makin jelas dibaca */
        }

        /* Mengatur kolom inputan */
        .form-control {
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 8px;
        }

        /* Tombol masuk warna biru medis/klinik cerah */
        .btn-masuk {
            background-color: #00a2e8;
            border: none;
            color: white;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-masuk:hover {
            background-color: #008cc8;
            color: white;
        }
    </style>
</head>
<body>

    <div class="card card-login p-4">
        <div class="card-body">
            
            <div class="text-center mb-4">
                <h3 class="text-white fw-bold mb-1" style="letter-spacing: 1px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">🏥 KlinikMedika</h3>
                <p class="text-white-50 small mb-0" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Sistem Informasi & Janji Temu Medis</p>
            </div>

            <form action="proses_login.php" method="POST">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username Pengguna</label>
                    <input type="text" name="username" class="form-control py-2" placeholder="Masukkan username" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control py-2" placeholder="••••" required>
                </div>

                <button type="submit" name="login" class="btn btn-masuk w-100 fw-bold py-2 mb-3">Masuk ke Sistem</button>
            </form>

            <p class="text-center mb-0 small text-white-50" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                Pasien baru? <a href="registrasi.php" class="text-white fw-semibold text-decoration-none">Daftar Akun Baru</a>
            </p>
        </div>
    </div>

</body>
</html>