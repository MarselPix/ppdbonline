<?php
session_start();
require '../config/database.php';
include 'templates/header.php';

$success_message = '';

// Handle Update Pengaturan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_pengaturan'])) {
    $pendaftaran_buka = $_POST['pendaftaran_buka'];
    $pendaftaran_tutup = $_POST['pendaftaran_tutup'];
    $pengumuman_tanggal = $_POST['pengumuman_tanggal'];
    $tahun_akademik = $_POST['tahun_akademik'];

    $query = "UPDATE pengaturan SET pendaftaran_buka = ?, pendaftaran_tutup = ?, pengumuman_tanggal = ?, tahun_akademik = ? WHERE id = 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $pendaftaran_buka, $pendaftaran_tutup, $pengumuman_tanggal, $tahun_akademik);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Pengaturan berhasil diperbarui.";
    } else {
        $error_message = "Gagal memperbarui pengaturan.";
    }
}

// Ambil data pengaturan saat ini
$pengaturan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan WHERE id = 1"));

?>

<h1 class="mb-4">Pengaturan Jadwal & Umum</h1>

<div class="card">
    <div class="card-header">Konfigurasi Sistem</div>
    <div class="card-body">
        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="pengaturan.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                    <input type="text" class="form-control" id="tahun_akademik" name="tahun_akademik" value="<?php echo htmlspecialchars($pengaturan['tahun_akademik']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="pendaftaran_buka" class="form-label">Pendaftaran Dibuka</label>
                    <input type="datetime-local" class="form-control" id="pendaftaran_buka" name="pendaftaran_buka" value="<?php echo date('Y-m-d\TH:i', strtotime($pengaturan['pendaftaran_buka'])); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="pendaftaran_tutup" class="form-label">Pendaftaran Ditutup</label>
                    <input type="datetime-local" class="form-control" id="pendaftaran_tutup" name="pendaftaran_tutup" value="<?php echo date('Y-m-d\TH:i', strtotime($pengaturan['pendaftaran_tutup'])); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="pengumuman_tanggal" class="form-label">Tanggal Pengumuman</label>
                    <input type="datetime-local" class="form-control" id="pengumuman_tanggal" name="pengumuman_tanggal" value="<?php echo date('Y-m-d\TH:i', strtotime($pengaturan['pengumuman_tanggal'])); ?>">
                </div>
            </div>
            <button type="submit" name="simpan_pengaturan" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
