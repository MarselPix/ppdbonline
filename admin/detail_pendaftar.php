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

<?php include 'templates/footer.php'; ?>