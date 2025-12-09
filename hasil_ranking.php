<?php
require 'config/database.php';
include 'templates/header.php';

// Ambil pengaturan untuk mengecek tanggal pengumuman
$pengaturan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT pengumuman_tanggal FROM pengaturan WHERE id = 1"));
$pengumuman_tanggal = new DateTime($pengaturan['pengumuman_tanggal']);
$sekarang = new DateTime();

$is_pengumuman_ready = $sekarang >= $pengumuman_tanggal;

$selected_jalur = $_GET['jalur'] ?? null;
$ranked_list = [];

if ($is_pengumuman_ready && $selected_jalur) {
    // Ambil data yang sudah diranking untuk jalur yang dipilih
    $query = "SELECT no_pendaftaran, nama_lengkap, asal_sekolah, rata_rata_nilai_rapot, ranking 
              FROM calon_siswa 
              WHERE jalur_pendaftaran = ? AND ranking IS NOT NULL AND status IN ('DITERIMA', 'DITOLAK')
              ORDER BY ranking ASC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $selected_jalur);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ranked_list = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Hasil Akhir Seleksi & Perangkingan PPDB</h4>
                </div>
                <div class="card-body">
                    <?php if ($is_pengumuman_ready): ?>
                        <p>Silakan pilih jalur pendaftaran untuk melihat hasil akhir seleksi dan perangkingan.</p>
                        
                        <!-- Pilihan Jalur -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mb-4">
                            <a href="?jalur=PRESTASI_AKADEMIK" class="btn <?php echo ($selected_jalur == 'PRESTASI_AKADEMIK') ? 'btn-primary' : 'btn-outline-primary'; ?>">Prestasi Akademik</a>
                            <a href="?jalur=PRESTASI_NON_AKADEMIK" class="btn <?php echo ($selected_jalur == 'PRESTASI_NON_AKADEMIK') ? 'btn-primary' : 'btn-outline-primary'; ?>">Prestasi Non-Akademik</a>
                            <a href="?jalur=AFIRMASI" class="btn <?php echo ($selected_jalur == 'AFIRMASI') ? 'btn-primary' : 'btn-outline-primary'; ?>">Afirmasi</a>
                        </div>

                        <!-- Tabel Hasil Ranking (jika jalur dipilih) -->
                        <?php if ($selected_jalur): ?>
                            <h5 class="mt-4">Jalur: <?php echo str_replace('_', ' ', htmlspecialchars($selected_jalur)); ?></h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Ranking</th>
                                            <th>No. Pendaftaran</th>
                                            <th>Nama Lengkap</th>
                                            <th>Asal Sekolah</th>
                                            <th class="text-center">Nilai Acuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($ranked_list)): ?>
                                            <?php foreach($ranked_list as $siswa): ?>
                                            <tr>
                                                <td class="text-center"><?php echo htmlspecialchars($siswa['ranking']); ?></td>
                                                <td><?php echo htmlspecialchars($siswa['no_pendaftaran']); ?></td>
                                                <td><?php echo htmlspecialchars($siswa['nama_lengkap']); ?></td>
                                                <td><?php echo htmlspecialchars($siswa['asal_sekolah']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($siswa['rata_rata_nilai_rapot'] ?? 'N/A'); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="5" class="text-center text-muted">Belum ada data yang diproses untuk jalur ini.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mt-4" role="alert">
                                <strong>Informasi Tambahan:</strong> Informasi mengenai daftar ulang akan diumumkan selanjutnya.
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <h5 class="alert-heading">Hasil Belum Tersedia</h5>
                            <p>Hasil akhir seleksi dan perangkingan akan ditampilkan pada tanggal: 
                            <strong><?php echo $pengumuman_tanggal->format('d F Y, H:i'); ?> WIB</strong>.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>