<?php
session_start();
require 'config/database.php';

// Cek jika user belum login, tendang ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data lengkap calon siswa dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM calon_siswa WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student_data = mysqli_fetch_assoc($result);

// Menentukan warna badge berdasarkan status
$status_badge_class = '';
switch ($student_data['status']) {
    case 'DITERIMA':
        $status_badge_class = 'bg-success';
        break;
    case 'DITOLAK':
        $status_badge_class = 'bg-danger';
        break;
    case 'PENGAJUAN':
        $status_badge_class = 'bg-warning text-dark';
        break;
    case 'BARU_DAFTAR':
    default:
        $status_badge_class = 'bg-info text-dark';
        break;
}

include 'templates/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Dashboard Calon Siswa</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>Selamat Datang, <?php echo htmlspecialchars($student_data['nama_lengkap']); ?>!</h5>
                        <p>Ini adalah halaman dashboard Anda. Silakan periksa status pendaftaran Anda dan lengkapi data jika diperlukan.</p>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th width="30%">Nomor Pendaftaran</th>
                                <td width="70%"><?php echo htmlspecialchars($student_data['no_pendaftaran']); ?></td>
                            </tr>
                            <tr>
                                <th>Status Pendaftaran</th>
                                <td>
                                    <span class="badge <?php echo $status_badge_class; ?>">
                                        <?php echo str_replace('_', ' ', htmlspecialchars($student_data['status'])); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 text-center align-self-center">
                        <?php if ($student_data['status'] == 'BARU_DAFTAR'): ?>
                            <p class="lead">Langkah selanjutnya:</p>
                            <a href="formulir.php" class="btn btn-primary btn-lg">Lengkapi Formulir Pendaftaran</a>
                        <?php elseif ($student_data['status'] == 'PENGAJUAN'): ?>
                            <p class="lead">Data Anda sedang dalam proses verifikasi oleh panitia. Mohon ditunggu.</p>
                        <?php elseif ($student_data['status'] == 'DITERIMA'): ?>
                             <div class="alert alert-success">
                                <h5>SELAMAT!</h5>
                                <p>Anda telah diterima di SMPN 1 Bawang. Informasi lebih lanjut akan disampaikan oleh panitia.</p>
                             </div>
                        <?php elseif ($student_data['status'] == 'DITOLAK'): ?>
                            <div class="alert alert-danger">
                                <h5>MOHON MAAF</h5>
                                <p>Anda belum berhasil lolos seleksi PPDB kali ini. Tetap semangat!</p>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
