<?php
session_start();
require '../config/database.php';
include 'templates/header.php';

$success_message = '';

// Handle proses seleksi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proses_seleksi'])) {
    $kuota = [
        'PRESTASI_AKADEMIK' => (int)$_POST['kuota_akademik'],
        'PRESTASI_NON_AKADEMIK' => (int)$_POST['kuota_non_akademik'],
        'AFIRMASI' => (int)$_POST['kuota_afirmasi']
    ];

    $update_status_query = "UPDATE calon_siswa SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_status_query);

    foreach ($kuota as $jalur => $q) {
        // Ambil semua siswa yang sudah diranking di jalur ini
        $query_ranked = "SELECT id, ranking FROM calon_siswa WHERE jalur_pendaftaran = ? AND ranking IS NOT NULL ORDER BY ranking ASC";
        $stmt_ranked = mysqli_prepare($conn, $query_ranked);
        mysqli_stmt_bind_param($stmt_ranked, "s", $jalur);
        mysqli_stmt_execute($stmt_ranked);
        $result_ranked = mysqli_stmt_get_result($stmt_ranked);

        while ($siswa = mysqli_fetch_assoc($result_ranked)) {
            $new_status = ($siswa['ranking'] <= $q) ? 'DITERIMA' : 'DITOLAK';
            mysqli_stmt_bind_param($stmt, "si", $new_status, $siswa['id']);
            mysqli_stmt_execute($stmt);
        }
    }

    $success_message = "Proses seleksi akhir berhasil dijalankan berdasarkan kuota yang ditentukan.";
}

?>

<h1 class="mb-4">Penentuan Kelulusan</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Proses Seleksi Akhir</h5>
        <p>Masukkan jumlah kuota (daya tampung) untuk setiap jalur pendaftaran. Klik tombol proses untuk menentukan status kelulusan (DITERIMA/DITOLAK) secara otomatis berdasarkan ranking siswa.</p>
        
        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="seleksi.php" method="POST">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="kuota_akademik" class="form-label">Kuota Prestasi Akademik</label>
                    <input type="number" class="form-control" id="kuota_akademik" name="kuota_akademik" value="50" required>
                </div>
                <div class="col-md-3">
                    <label for="kuota_non_akademik" class="form-label">Kuota Prestasi Non-Akademik</label>
                    <input type="number" class="form-control" id="kuota_non_akademik" name="kuota_non_akademik" value="20" required>
                </div>
                <div class="col-md-3">
                    <label for="kuota_afirmasi" class="form-label">Kuota Afirmasi</label>
                    <input type="number" class="form-control" id="kuota_afirmasi" name="kuota_afirmasi" value="30" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="proses_seleksi" class="btn btn-danger w-100" onclick="return confirm('Proses ini akan mengubah status semua siswa yang telah diranking. Lanjutkan?')">Proses Seleksi Akhir</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Hasil Seleksi</div>
    <div class="card-body">
        <p>Tabel di bawah ini menunjukkan status final pendaftar. Anda masih bisa mengubah status secara manual di halaman "Manajemen Pendaftar" jika diperlukan.</p>
        <!-- Di sini bisa ditambahkan tabel untuk menampilkan hasil seleksi jika perlu -->
    </div>
</div>


<?php include 'templates/footer.php'; ?>
