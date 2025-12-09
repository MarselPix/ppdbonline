<?php
// Menonaktifkan tampilan error di browser
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
session_start();
require 'config/database.php';
include 'templates/header.php';

$pengaturan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan WHERE id = 1"));
$pengumuman_tanggal = new DateTime($pengaturan['pengumuman_tanggal']);
$sekarang = new DateTime();

$is_pengumuman_ready = $sekarang >= $pengumuman_tanggal;
$hasil_seleksi = null;

if (isset($_GET['no_pendaftaran']) && !empty($_GET['no_pendaftaran'])) {
    if ($is_pengumuman_ready) {
        $no_pendaftaran = mysqli_real_escape_string($conn, $_GET['no_pendaftaran']);
        $query = "SELECT nama_lengkap, status, ranking FROM calon_siswa WHERE no_pendaftaran = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $no_pendaftaran);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $hasil_seleksi = mysqli_fetch_assoc($result);
    } 
}

?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card text-center">
            <div class="card-header">
                <h4>Pengumuman Hasil Seleksi PPDB</h4>
            </div>
            <div class="card-body">
                <?php if ($is_pengumuman_ready): ?>
                    <h5 class="card-title">Masukkan Nomor Pendaftaran Anda</h5>
                    <form action="pengumuman.php" method="GET" class="row justify-content-center g-2">
                        <div class="col-auto">
                            <input type="text" class="form-control" name="no_pendaftaran" placeholder="Contoh: PPDB202400001" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>

                    <?php if ($hasil_seleksi !== null): ?>
                        <hr>
                        <div class="mt-4">
                            <?php if ($hasil_seleksi['status'] == 'DITERIMA'): ?>
                                <div class="alert alert-success">
                                    <h4 class="alert-heading">SELAMAT!</h4>
                                    <p><strong><?php echo htmlspecialchars($hasil_seleksi['nama_lengkap']); ?></strong>, Anda dinyatakan <strong>DITERIMA</strong> di SMP Negeri 1 Bawang.</p>
                                    <hr>
                                    <p class="mb-0">Informasi mengenai daftar ulang akan diumumkan selanjutnya.</p>
                                </div>
                            <?php elseif ($hasil_seleksi['status'] == 'DITOLAK'): ?>
                                <div class="alert alert-danger">
                                    <h4 class="alert-heading">MOHON MAAF</h4>
                                    <p><strong><?php echo htmlspecialchars($hasil_seleksi['nama_lengkap']); ?></strong>, Anda dinyatakan <strong>TIDAK DITERIMA</strong> pada seleksi PPDB kali ini.</p>
                                    <hr>
                                    <p class="mb-0">Jangan berkecil hati dan tetap semangat.</p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <p>Hasil seleksi untuk nomor pendaftaran ini belum dapat ditampilkan atau masih dalam proses.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (isset($_GET['no_pendaftaran'])): ?>
                        <hr>
                        <div class="alert alert-warning mt-4">
                            <p>Nomor pendaftaran tidak ditemukan.</p>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <h5 class="card-title">Pengumuman Belum Tersedia</h5>
                    <p>Hasil seleksi akan diumumkan pada tanggal: <strong><?php echo $pengumuman_tanggal->format('d F Y, H:i'); ?> WIB</strong></p>
                    <!-- Optional: Countdown Timer -->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>