<?php
session_start();
require '../config/database.php';
include 'templates/header.php';

$success_message = '';

// Handle proses perangkingan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hitung_ranking'])) {
    // 1. Ambil semua siswa dengan status PENGAJUAN
    $query_pengajuan = "SELECT * FROM calon_siswa WHERE status = 'PENGAJUAN'";
    $result_pengajuan = mysqli_query($conn, $query_pengajuan);

    $siswa_per_jalur = [
        'PRESTASI_AKADEMIK' => [],
        'PRESTASI_NON_AKADEMIK' => [],
        'AFIRMASI' => []
    ];

    while ($siswa = mysqli_fetch_assoc($result_pengajuan)) {
        $siswa_per_jalur[$siswa['jalur_pendaftaran']][] = $siswa;
    }

    // 2. Sort setiap jalur dan update ranking
    // Jalur Prestasi Akademik: berdasarkan nilai rapot DESC
    usort($siswa_per_jalur['PRESTASI_AKADEMIK'], function($a, $b) {
        return $b['rata_rata_nilai_rapot'] <=> $a['rata_rata_nilai_rapot'];
    });

    // Jalur Afirmasi: berdasarkan nilai rapot DESC
    usort($siswa_per_jalur['AFIRMASI'], function($a, $b) {
        return $b['rata_rata_nilai_rapot'] <=> $a['rata_rata_nilai_rapot'];
    });

    // Jalur Prestasi Non-Akademik: berdasarkan skor prestasi DESC
    usort($siswa_per_jalur['PRESTASI_NON_AKADEMIK'], function($a, $b) {
        return $b['skor_prestasi'] <=> $a['skor_prestasi'];
    });

    // 3. Update database dengan ranking baru
    $update_query = "UPDATE calon_siswa SET ranking = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);

    foreach ($siswa_per_jalur as $jalur => $siswas) {
        $rank = 1;
        foreach ($siswas as $siswa) {
            mysqli_stmt_bind_param($stmt, "ii", $rank, $siswa['id']);
            mysqli_stmt_execute($stmt);
            $rank++;
        }
    }

    $success_message = "Proses perangkingan berhasil diselesaikan!";
}

// Ambil data yang sudah diranking untuk ditampilkan
$ranked_lists = [];
$jalur_types = ['PRESTASI_AKADEMIK', 'PRESTASI_NON_AKADEMIK', 'AFIRMASI'];
foreach ($jalur_types as $jalur) {
    $query = "SELECT no_pendaftaran, nama_lengkap, rata_rata_nilai_rapot, skor_prestasi, ranking FROM calon_siswa WHERE jalur_pendaftaran = ? AND ranking IS NOT NULL ORDER BY ranking ASC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $jalur);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ranked_lists[$jalur] = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<h1 class="mb-4">Proses Perangkingan</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Mulai Perangkingan</h5>
        <p>Klik tombol di bawah untuk memulai proses perangkingan otomatis untuk semua pendaftar dengan status "PENGAJUAN". Sistem akan mengurutkan pendaftar sesuai kriteria di setiap jalur pendaftaran.</p>
        <form action="ranking.php" method="POST">
            <button type="submit" name="hitung_ranking" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin menjalankan proses ini? Ranking yang ada akan dihitung ulang.')">Hitung Ranking Sekarang</button>
        </form>
        <?php if(!empty($success_message)): ?>
            <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
        <?php endif; ?>
    </div>
</div>

<?php foreach ($jalur_types as $jalur): ?>
<div class="card mb-4">
    <div class="card-header">Hasil Ranking Jalur: <?php echo str_replace('_', ' ', $jalur); ?></div>
    <div class="card-body">
        <table class="table table-sm table-bordered">
            <thead><tr><th>Ranking</th><th>No. Pendaftaran</th><th>Nama Lengkap</th><th>Nilai Acuan</th></tr></thead>
            <tbody>
                <?php if(!empty($ranked_lists[$jalur])): ?>
                    <?php foreach($ranked_lists[$jalur] as $siswa): ?>
                    <tr>
                        <td><?php echo $siswa['ranking']; ?></td>
                        <td><?php echo $siswa['no_pendaftaran']; ?></td>
                        <td><?php echo $siswa['nama_lengkap']; ?></td>
                        <td>
                            <?php 
                                if ($jalur == 'PRESTASI_NON_AKADEMIK') {
                                    echo htmlspecialchars($siswa['skor_prestasi'] ?? 'N/A');
                                } else {
                                    echo htmlspecialchars($siswa['rata_rata_nilai_rapot'] ?? 'N/A');
                                }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">Belum ada data yang diranking.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endforeach; ?>

<?php include 'templates/footer.php'; ?>
