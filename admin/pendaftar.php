<?php
session_start();
require '../config/database.php';
include 'templates/header.php';

// Handle Aksi (Terima/Tolak)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];

    $new_status = '';
    if ($action == 'terima') {
        $new_status = 'DITERIMA';
    } elseif ($action == 'tolak') {
        $new_status = 'DITOLAK';
    }

    if (!empty($new_status)) {
        $query_update = "UPDATE calon_siswa SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query_update);
        mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
        mysqli_stmt_execute($stmt);
        header("Location: pendaftar.php"); // Redirect untuk refresh halaman
        exit();
    }
}

// Filter dan Search
$where_clauses = [];
$filter_jalur = $_GET['filter_jalur'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$search_query = $_GET['search'] ?? '';

if (!empty($filter_jalur)) {
    $where_clauses[] = "jalur_pendaftaran = '" . mysqli_real_escape_string($conn, $filter_jalur) . "'";
}
if (!empty($filter_status)) {
    $where_clauses[] = "status = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
}
if (!empty($search_query)) {
    $where_clauses[] = "(nama_lengkap LIKE '%%" . mysqli_real_escape_string($conn, $search_query) . "%%' OR no_pendaftaran LIKE '%%" . mysqli_real_escape_string($conn, $search_query) . "%%')";
}

$sql = "SELECT id, no_pendaftaran, nama_lengkap, jalur_pendaftaran, status FROM calon_siswa";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<h1 class="mb-4">Manajemen Pendaftar</h1>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form action="pendaftar.php" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama/No. Pendaftaran..." value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <div class="col-md-3">
                <select name="filter_jalur" class="form-select">
                    <option value="">Semua Jalur</option>
                    <option value="PRESTASI_AKADEMIK" <?php echo ($filter_jalur == 'PRESTASI_AKADEMIK') ? 'selected' : ''; ?>>Prestasi Akademik</option>
                    <option value="PRESTASI_NON_AKADEMIK" <?php echo ($filter_jalur == 'PRESTASI_NON_AKADEMIK') ? 'selected' : ''; ?>>Prestasi Non-Akademik</option>
                    <option value="AFIRMASI" <?php echo ($filter_jalur == 'AFIRMASI') ? 'selected' : ''; ?>>Afirmasi</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="filter_status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="BARU_DAFTAR" <?php echo ($filter_status == 'BARU_DAFTAR') ? 'selected' : ''; ?>>Baru Daftar</option>
                    <option value="PENGAJUAN" <?php echo ($filter_status == 'PENGAJUAN') ? 'selected' : ''; ?>>Pengajuan</option>
                    <option value="DITERIMA" <?php echo ($filter_status == 'DITERIMA') ? 'selected' : ''; ?>>Diterima</option>
                    <option value="DITOLAK" <?php echo ($filter_status == 'DITOLAK') ? 'selected' : ''; ?>>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Pendaftar -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama Lengkap</th>
                        <th>Jalur</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['no_pendaftaran']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?php echo str_replace('_', ' ', htmlspecialchars($row['jalur_pendaftaran'])); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo str_replace('_', ' ', htmlspecialchars($row['status'])); ?></span></td>
                            <td>
                                <a href="detail_pendaftar.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">Detail</a>
                                <a href="pendaftar.php?action=terima&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin menerima siswa ini?')">Terima</a>
                                <a href="pendaftar.php?action=tolak&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menolak siswa ini?')">Tolak</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data pendaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
