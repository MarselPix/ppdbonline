<?php
session_start();
require '../config/database.php';
include 'templates/header.php';

// Cek ID dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: pendaftar.php");
    exit();
}

$id = (int)$_GET['id'];

// Handle Simpan Skor Prestasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_skor'])) {
    $prestasi_tingkat = $_POST['prestasi_tingkat'];
    $prestasi_peringkat = $_POST['prestasi_peringkat'];
    $skor = 0;

    // Scoring Matrix
    $skor_map = [
        'INTERNASIONAL' => ['JUARA_1' => 100, 'JUARA_2' => 95, 'JUARA_3' => 90],
        'NASIONAL'      => ['JUARA_1' => 90, 'JUARA_2' => 85, 'JUARA_3' => 80],
        'PROVINSI'      => ['JUARA_1' => 80, 'JUARA_2' => 75, 'JUARA_3' => 70],
        'KABUPATEN_KOTA' => ['JUARA_1' => 70, 'JUARA_2' => 65, 'JUARA_3' => 60],
    ];

    if (isset($skor_map[$prestasi_tingkat][$prestasi_peringkat])) {
        $skor = $skor_map[$prestasi_tingkat][$prestasi_peringkat];
    }

    // Update database
    $update_skor_query = "UPDATE calon_siswa SET prestasi_tingkat = ?, prestasi_peringkat = ?, skor_prestasi = ? WHERE id = ?";
    $stmt_skor = mysqli_prepare($conn, $update_skor_query);
    mysqli_stmt_bind_param($stmt_skor, "ssii", $prestasi_tingkat, $prestasi_peringkat, $skor, $id);

    if (mysqli_stmt_execute($stmt_skor)) {
        // Redirect untuk refresh halaman dan menampilkan pesan sukses
        header("Location: detail_pendaftar.php?id=$id&skor_saved=1");
        exit();
    } else {
        $error_message = "Gagal menyimpan skor.";
    }
}

// Ambil data lengkap siswa
$query = "SELECT * FROM calon_siswa WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

// Jika tidak ada data siswa, kembali ke halaman pendaftar
if (!$siswa) {
    header("Location: pendaftar.php");
    exit();
}

// Helper function to create a link for a file
function render_file_link($file) {
    if (!empty($file)) {
        return '<a href="../uploads/' . htmlspecialchars($file) . '" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Berkas</a>';
    }
    return '<span class="text-muted">Tidak ada berkas</span>';
}

?>

<h1 class="mb-4">Detail Pendaftar</h1>

<?php if(isset($_GET['skor_saved'])) { echo '<div class="alert alert-success">Skor prestasi berhasil disimpan.</div>'; } ?>
<?php if(isset($error_message)) { echo '<div class="alert alert-danger">'.$error_message.'</div>'; } ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Data untuk No. Pendaftaran: <?php echo htmlspecialchars($siswa['no_pendaftaran']); ?></h5>
        <a href="pendaftar.php" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Data Pribadi Siswa</h5>
                <table class="table table-bordered">
                    <tr><th width="40%">Nama Lengkap</th><td><?php echo htmlspecialchars($siswa['nama_lengkap']); ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td><?php echo htmlspecialchars($siswa['jenis_kelamin']); ?></td></tr>
                    <tr><th>Tempat, Tgl Lahir</th><td><?php echo htmlspecialchars($siswa['tempat_lahir']) . ', ' . date('d M Y', strtotime($siswa['tanggal_lahir'])); ?></td></tr>
                    <tr><th>Alamat</th><td><?php echo htmlspecialchars($siswa['alamat']); ?></td></tr>
                    <tr><th>Asal Sekolah</th><td><?php echo htmlspecialchars($siswa['asal_sekolah']); ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Data Orang Tua & Kontak</h5>
                <table class="table table-bordered">
                    <tr><th width="40%">Nama Orang Tua/Wali</th><td><?php echo htmlspecialchars($siswa['nama_ortu']); ?></td></tr>
                    <tr><th>No. HP</th><td><?php echo htmlspecialchars($siswa['no_hp_ortu']); ?></td></tr>
                    <tr><th>Email</th><td><?php echo htmlspecialchars($siswa['email_ortu']); ?></td></tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <h5>Data Pendaftaran</h5>
                <table class="table table-bordered">
                    <tr><th width="40%">Jalur Pendaftaran</th><td><?php echo str_replace('_', ' ', htmlspecialchars($siswa['jalur_pendaftaran'])); ?></td></tr>
                    <?php if ($siswa['jalur_pendaftaran'] == 'PRESTASI_AKADEMIK'): ?>
                        <tr><th>Rata-rata Nilai Rapot</th><td><?php echo htmlspecialchars($siswa['rata_rata_nilai_rapot']); ?></td></tr>
                    <?php elseif ($siswa['jalur_pendaftaran'] == 'PRESTASI_NON_AKADEMIK'): ?>
                        <tr><th>Berkas Prestasi</th><td><?php echo render_file_link($siswa['berkas_prestasi']); ?></td></tr>
                        <tr><th>Skor Prestasi</th><td><strong><?php echo htmlspecialchars($siswa['skor_prestasi'] ?? 0); ?></strong></td></tr>
                    <?php elseif ($siswa['jalur_pendaftaran'] == 'AFIRMASI'): ?>
                        <tr><th>No. KIP</th><td><?php echo htmlspecialchars($siswa['no_kip']); ?></td></tr>
                        <tr><th>Berkas KIP</th><td><?php echo render_file_link($siswa['berkas_kip']); ?></td></tr>
                    <?php endif; ?>
                    <tr><th>Status Saat Ini</th><td><span class="badge bg-info text-dark"><?php echo str_replace('_', ' ', htmlspecialchars($siswa['status'])); ?></span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Berkas Wajib</h5>
                <table class="table table-bordered">
                    <tr><th width="40%">Akta Kelahiran</th><td><?php echo render_file_link($siswa['berkas_akta_lahir']); ?></td></tr>
                    <tr><th>Kartu Keluarga (KK)</th><td><?php echo render_file_link($siswa['berkas_kk']); ?></td></tr>
                    <tr><th>Surat Ket. Lulus (SKL)</th><td><?php echo render_file_link($siswa['berkas_skl']); ?></td></tr>
                    <tr><th>Surat Pernyataan</th><td><?php echo render_file_link($siswa['berkas_surat_pernyataan']); ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($siswa['jalur_pendaftaran'] == 'PRESTASI_NON_AKADEMIK'): ?>
<div class="card mt-4">
    <div class="card-header">
        <h5>Penilaian Skor Prestasi Non-Akademik</h5>
    </div>
    <div class="card-body">
        <form action="detail_pendaftar.php?id=<?php echo $id; ?>" method="POST">
            <p>Berdasarkan berkas prestasi yang di-upload, silakan tentukan tingkat dan peringkat prestasi siswa untuk perhitungan skor otomatis.</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="prestasi_tingkat" class="form-label">Tingkat Kejuaraan</label>
                    <select name="prestasi_tingkat" class="form-select" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="INTERNASIONAL" <?php echo ($siswa['prestasi_tingkat'] == 'INTERNASIONAL') ? 'selected' : ''; ?>>Internasional</option>
                        <option value="NASIONAL" <?php echo ($siswa['prestasi_tingkat'] == 'NASIONAL') ? 'selected' : ''; ?>>Nasional</option>
                        <option value="PROVINSI" <?php echo ($siswa['prestasi_tingkat'] == 'PROVINSI') ? 'selected' : ''; ?>>Provinsi</option>
                        <option value="KABUPATEN_KOTA" <?php echo ($siswa['prestasi_tingkat'] == 'KABUPATEN_KOTA') ? 'selected' : ''; ?>>Kabupaten/Kota</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="prestasi_peringkat" class="form-label">Peringkat</label>
                    <select name="prestasi_peringkat" class="form-select" required>
                        <option value="">-- Pilih Peringkat --</option>
                        <option value="JUARA_1" <?php echo ($siswa['prestasi_peringkat'] == 'JUARA_1') ? 'selected' : ''; ?>>Juara 1</option>
                        <option value="JUARA_2" <?php echo ($siswa['prestasi_peringkat'] == 'JUARA_2') ? 'selected' : ''; ?>>Juara 2</option>
                        <option value="JUARA_3" <?php echo ($siswa['prestasi_peringkat'] == 'JUARA_3') ? 'selected' : ''; ?>>Juara 3</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="simpan_skor" class="btn btn-primary">Simpan & Hitung Skor</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>