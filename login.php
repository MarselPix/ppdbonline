<?php
// Menonaktifkan tampilan error di browser
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
session_start();
require 'config/database.php';

$error_message = '';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_pendaftaran = mysqli_real_escape_string($conn, $_POST['no_pendaftaran']);
    $password = $_POST['password'];

    if (empty($no_pendaftaran) || empty($password)) {
        $error_message = "Nomor pendaftaran dan password wajib diisi.";
    } else {
        $query = "SELECT id, no_pendaftaran, password FROM calon_siswa WHERE no_pendaftaran = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $no_pendaftaran);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Password benar, buat session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['no_pendaftaran'] = $user['no_pendaftaran'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Nomor pendaftaran atau password salah.";
            }
        } else {
            $error_message = "Nomor pendaftaran atau password salah.";
        }
    }
}

// Ambil pesan sukses dari session jika ada (dari halaman registrasi)
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan
}

include 'templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">Login Calon Siswa</h4>
            </div>
            <div class="card-body">
                <?php if(!empty($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="no_pendaftaran" class="form-label">Nomor Pendaftaran</label>
                        <input type="text" class="form-control" id="no_pendaftaran" name="no_pendaftaran" placeholder="Contoh: PPDB202400001" required>
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
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>



<?php include 'templates/footer.php'; ?>