<?php include 'templates/header.php'; ?>

<div class="hero-section rounded mb-5">
    <div class="container">
        <h1 class="display-4">Selamat Datang di PPDB Online</h1>
        <p class="lead">SMP Negeri 1 Bawang - Tahun Ajaran 2024/2025</p>
        <hr class="my-4">
        <p>Pendaftaran siswa baru telah dibuka. Silakan klik tombol di bawah untuk memulai.</p>
        <a class="btn btn-primary btn-lg" href="register.php" role="button">Daftar Sekarang</a>
        <a class="btn btn-success btn-lg" href="pengumuman.php" role="button">Cek Pengumuman</a>
    </div>
</div>

<div class="row text-center mb-5">
    <div class="col-md-12 mb-4">
        <h2>Alur Pendaftaran</h2>
        <p class="lead">Ikuti langkah-langkah mudah berikut untuk mendaftar.</p>
    </div>
    <div class="col-md-3 alur-item">
        <div class="icon">1</div>
        <h5>Buat Akun</h5>
        <p>Daftar akun untuk mendapatkan No. Pendaftaran & password.</p>
    </div>
    <div class="col-md-3 alur-item">
        <div class="icon">2</div>
        <h5>Lengkapi Formulir</h5>
        <p>Login dan lengkapi semua data diri, data orang tua, dan upload berkas.</p>
    </div>
    <div class="col-md-3 alur-item">
        <div class="icon">3</div>
        <h5>Verifikasi Panitia</h5>
        <p>Panitia akan memeriksa kelengkapan dan keabsahan data Anda.</p>
    </div>
    <div class="col-md-3 alur-item">
        <div class="icon">4</div>
        <h5>Lihat Pengumuman</h5>
        <p>Cek status kelulusan Anda pada tanggal yang telah ditentukan.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <h2>Jalur Pendaftaran</h2>
        <p class="lead">Pilih jalur pendaftaran yang sesuai dengan kriteria Anda.</p>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
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
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
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
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Afirmasi</h5>
                <p class="card-text">Untuk calon siswa dari keluarga kurang mampu yang terdaftar dalam program pemerintah.</p>
                <h6>Syarat:</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Kartu Indonesia Pintar (KIP)</li>
                    <li class="list-group-item">Surat Keterangan Lulus</li>
                    <li class="list-group-item">Akta Kelahiran & Kartu Keluarga</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
