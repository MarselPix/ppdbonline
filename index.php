<?php
// Menonaktifkan tampilan error di browser
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php include 'templates/header.php'; ?>

<!-- Hero Section Baru -->
<div class="hero-section-container">
    <div class="hero-overlay"></div>
    <div class="hero-content text-center text-white">
        <h1 class="display-4 fw-bold">Selamat Datang di PPDB Online</h1>
        <p class="lead fs-4">SMP Negeri 2 Bawang - Tahun Ajaran 2024/2025</p>
        <hr class="my-4 border-white">
        <p class="fs-5">Pendaftaran siswa baru telah dibuka. Segera daftarkan diri Anda!</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a class="btn btn-primary btn-lg px-4 gap-3" href="register.php" role="button">Daftar Sekarang <i class="bi bi-arrow-right-circle-fill ms-2"></i></a>
            <a class="btn btn-outline-light btn-lg px-4" href="pengumuman.php" role="button">Cek Pengumuman</a>
        </div>
    </div>
</div>

<div class="container px-4 py-5">
    <!-- Alur Pendaftaran Baru -->
    <div class="row text-center mb-5">
        <div class="col-md-12 mb-4">
            <h2 class="display-5 fw-bold">Alur Pendaftaran</h2>
            <p class="lead text-muted">Ikuti 4 langkah mudah untuk mendaftar di sekolah kami.</p>
        </div>
    </div>
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-4">
        <div class="feature col">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h3 class="fs-2">Buat Akun</h3>
            <p>Daftarkan akun Anda untuk mendapatkan Nomor Pendaftaran dan kata sandi untuk login.</p>
        </div>
        <div class="feature col">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                <i class="bi bi-file-earmark-text-fill"></i>
            </div>
            <h3 class="fs-2">Lengkapi Formulir</h3>
            <p>Login dan lengkapi semua data diri, data orang tua, dan unggah berkas yang diperlukan.</p>
        </div>
        <div class="feature col">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                <i class="bi bi-shield-check"></i>
            </div>
            <h3 class="fs-2">Verifikasi Panitia</h3>
            <p>Panitia akan memeriksa kelengkapan dan keabsahan data dan berkas yang Anda kirimkan.</p>
        </div>
        <div class="feature col">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <h3 class="fs-2">Lihat Pengumuman</h3>
            <p>Cek status kelulusan Anda pada tanggal yang telah ditentukan melalui halaman pengumuman.</p>
        </div>
    </div>

    <!-- Jalur Pendaftaran (Tidak diubah, tapi akan terlihat lebih baik dengan style baru) -->
    <div class="row jalur-pendaftaran-section mt-5">
        <div class="col-md-12 text-center mb-4">
            <h2 class="display-5 fw-bold">Jalur Pendaftaran</h2>
            <p class="lead text-muted">Pilih jalur pendaftaran yang sesuai dengan kriteria Anda.</p>
        </div>
        <!-- Jalur Prestasi Akademik -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 card-jalur card-jalur-akademik">
                <div class="card-body text-center">
                    <div class="card-icon icon-akademik mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="card-title">Prestasi Akademik</h5>
                    <p class="card-text">Untuk calon siswa dengan nilai rapot yang unggul.</p>
                    <h6>Syarat:</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Scan Rapor Semester 1-5</li>
                        <li class="list-group-item">Surat Keterangan Lulus</li>
                        <li class="list-group-item">Akta Kelahiran & Kartu Keluarga</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Jalur Prestasi Non-Akademik -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 card-jalur card-jalur-non-akademik">
                <div class="card-body text-center">
                    <div class="card-icon icon-non-akademik mb-3">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <h5 class="card-title">Prestasi Non-Akademik</h5>
                    <p class="card-text">Untuk calon siswa dengan prestasi di bidang non-akademik (lomba, kejuaraan).</p>
                    <h6>Syarat:</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Sertifikat/Piagam Prestasi</li>
                        <li class="list-group-item">Surat Keterangan Lulus</li>
                        <li class="list-group-item">Akta Kelahiran & Kartu Keluarga</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Jalur Afirmasi -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 card-jalur card-jalur-afirmasi">
                <div class="card-body text-center">
                    <div class="card-icon icon-afirmasi mb-3">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="card-title">Afirmasi</h5>
                    <p class="card-text">Untuk calon siswa dari keluarga kurang mampu yang terdaftar dalam program pemerintah.</p>
                    <h6>Syarat:</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Kartu Indonesia Pintar (KIP)</li>
                        <li class="list-group-item">Surat Keterangan Lulus</li>
                        <li class="list-group-item">Akta Kelahiran (AK)& Kartu Keluarga (KK)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
