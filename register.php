<?php
// Menonaktifkan tampilan error di browser
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
session_start();
require 'config/database.php';

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan data dari form
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email_ortu = mysqli_real_escape_string($conn, $_POST['email_ortu']);
    $no_hp_ortu = mysqli_real_escape_string($conn, $_POST['no_hp_ortu']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validasi dasar
    if (empty($nama_lengkap) || empty($email_ortu) || empty($password)) {
        $error_message = "Semua field wajib diisi.";
    } elseif ($password !== $password_confirm) {
        $error_message = "Konfirmasi password tidak cocok.";
    } elseif (!filter_var($email_ortu, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        // Generate Nomor Pendaftaran Unik
        // Format: PPDB[TAHUN][5 digit nomor urut]
        $tahun = date('Y');
        $query_last_id = "SELECT MAX(id) as last_id FROM calon_siswa";
        $result_last_id = mysqli_query($conn, $query_last_id);
        $row_last_id = mysqli_fetch_assoc($result_last_id);
        $next_id = ($row_last_id['last_id'] ?? 0) + 1;
        $no_pendaftaran = sprintf("PPDB%s%05d", $tahun, $next_id);

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Default jalur pendaftaran (bisa diubah nanti di dashboard siswa)
        // Status awal adalah 'BARU_DAFTAR'
        $default_jalur = 'PRESTASI_AKADEMIK'; // Atau bisa dibuat NULL

        // Query untuk memasukkan data
        $query_insert = "INSERT INTO calon_siswa (no_pendaftaran, nama_lengkap, email_ortu, no_hp_ortu, password, jalur_pendaftaran, status) VALUES (?, ?, ?, ?, ?, ?, 'BARU_DAFTAR')";
        
        $stmt = mysqli_prepare($conn, $query_insert);
        mysqli_stmt_bind_param($stmt, "ssssss", $no_pendaftaran, $nama_lengkap, $email_ortu, $no_hp_ortu, $hashed_password, $default_jalur);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Pendaftaran berhasil! No Pendaftaran Anda adalah <strong>$no_pendaftaran</strong>. Silakan login untuk melengkapi data.";
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
        }
    }
}

include 'templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Buat Akun Pendaftaran</h4>
            </div>
            <div class="card-body">
                <?php if(!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap Calon Siswa</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_ortu" class="form-label">Email Orang Tua/Wali</label>
                        <input type="email" class="form-control" id="email_ortu" name="email_ortu" required>
                        <div class="form-text">Email ini akan digunakan untuk komunikasi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp_ortu" class="form-label">No. HP Orang Tua/Wali</label>
                        <input type="tel" class="form-control" id="no_hp_ortu" name="no_hp_ortu" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span class="input-group-text" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            <span class="input-group-text" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="togglePasswordConfirm"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</div>


<?php include 'templates/footer.php'; ?>