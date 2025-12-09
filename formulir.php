<?php
// Menonaktifkan tampilan error di browser
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
session_start();
require 'config/database.php';

// Cek jika user belum login, tendang ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

// Helper function untuk handle upload file
function handle_upload($file_key, $existing_file = null) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
        $target_dir = "uploads/";
        // Buat nama file unik untuk menghindari tumpukan
        $filename = uniqid() . '-' . basename($_FILES[$file_key]["name"]);
        if (move_uploaded_file($_FILES[$file_key]["tmp_name"], $target_dir . $filename)) {
            return $filename;
        }
    }
    return $existing_file;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil semua data dari form
    $jalur_pendaftaran = $_POST['jalur_pendaftaran'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $asal_sekolah = $_POST['asal_sekolah'];
    $nama_ortu = $_POST['nama_ortu'];
    $no_hp_ortu = $_POST['no_hp_ortu'];
    $email_ortu = $_POST['email_ortu'];

    // Data spesifik per jalur
    $rata_rata_nilai_rapot = ($jalur_pendaftaran == 'PRESTASI_AKADEMIK') ? $_POST['rata_rata_nilai_rapot'] : NULL;
    $no_kip = ($jalur_pendaftaran == 'AFIRMASI') ? $_POST['no_kip'] : NULL;

    // Handle file uploads
    $berkas_prestasi_filename = handle_upload('berkas_prestasi', $_POST['existing_berkas_prestasi']);
    $berkas_kip_filename = handle_upload('berkas_kip', $_POST['existing_berkas_kip']);
    $berkas_akta_lahir_filename = handle_upload('berkas_akta_lahir', $_POST['existing_berkas_akta_lahir']);
    $berkas_kk_filename = handle_upload('berkas_kk', $_POST['existing_berkas_kk']);
    $berkas_skl_filename = handle_upload('berkas_skl', $_POST['existing_berkas_skl']);
    $berkas_surat_pernyataan_filename = handle_upload('berkas_surat_pernyataan', $_POST['existing_berkas_surat_pernyataan']);

    // Update data di database
    $query = "UPDATE calon_siswa SET 
                jalur_pendaftaran = ?, nama_lengkap = ?, jenis_kelamin = ?, tempat_lahir = ?, 
                tanggal_lahir = ?, alamat = ?, asal_sekolah = ?, nama_ortu = ?, no_hp_ortu = ?, 
                email_ortu = ?, rata_rata_nilai_rapot = ?, berkas_prestasi = ?, no_kip = ?, 
                berkas_kip = ?, status = 'PENGAJUAN', berkas_akta_lahir = ?, berkas_kk = ?, 
                berkas_skl = ?, berkas_surat_pernyataan = ?
              WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssssssdsssssssi', 
        $jalur_pendaftaran, $nama_lengkap, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, 
        $alamat, $asal_sekolah, $nama_ortu, $no_hp_ortu, $email_ortu, 
        $rata_rata_nilai_rapot, $berkas_prestasi_filename, $no_kip, $berkas_kip_filename, 
        $berkas_akta_lahir_filename, $berkas_kk_filename, $berkas_skl_filename, $berkas_surat_pernyataan_filename,
        $user_id
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Data formulir berhasil disimpan dan diajukan. Status pendaftaran Anda telah diperbarui menjadi PENGAJUAN.";
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Gagal menyimpan data. Silakan coba lagi.";
    }
}

// Ambil data siswa untuk ditampilkan di form
$query_student = "SELECT * FROM calon_siswa WHERE id = ?";
$stmt_student = mysqli_prepare($conn, $query_student);
mysqli_stmt_bind_param($stmt_student, "i", $user_id);
mysqli_stmt_execute($stmt_student);
$result_student = mysqli_stmt_get_result($stmt_student);
$student = mysqli_fetch_assoc($result_student);

include 'templates/header.php';
?>

<div class="card">
    <div class="card-header">
        <h4>Formulir Pendaftaran Siswa Baru</h4>
    </div>
    <div class="card-body">
        <form action="formulir.php" method="POST" enctype="multipart/form-data">
            <!-- Data Diri -->
            <h5>Data Pribadi</h5>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Pendaftaran</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['no_pendaftaran']); ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($student['nama_lengkap']); ?>" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?php echo ($student['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo ($student['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($student['tempat_lahir']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($student['tanggal_lahir']); ?>" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                    <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" value="<?php echo htmlspecialchars($student['asal_sekolah']); ?>" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($student['alamat']); ?></textarea>
                </div>
            </div>

            <!-- Data Ortu -->
            <h5 class="mt-4">Data Orang Tua/Wali</h5>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_ortu" class="form-label">Nama Orang Tua/Wali</label>
                    <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" value="<?php echo htmlspecialchars($student['nama_ortu']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="no_hp_ortu" class="form-label">No. HP Orang Tua/Wali</label>
                    <input type="tel" class="form-control" id="no_hp_ortu" name="no_hp_ortu" value="<?php echo htmlspecialchars($student['no_hp_ortu']); ?>" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="email_ortu" class="form-label">Email Orang Tua/Wali</label>
                    <input type="email" class="form-control" id="email_ortu" name="email_ortu" value="<?php echo htmlspecialchars($student['email_ortu']); ?>" required>
                </div>
            </div>

            <!-- Berkas Wajib -->
            <h5 class="mt-4">Berkas Wajib</h5>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="berkas_akta_lahir" class="form-label">Upload Scan Akta Kelahiran</label>
                    <input type="file" class="form-control" id="berkas_akta_lahir" name="berkas_akta_lahir">
                    <?php if($student['berkas_akta_lahir']): ?> <small class="form-text text-success">File sudah ada: <a href="uploads/<?php echo $student['berkas_akta_lahir']; ?>" target="_blank">Lihat</a></small> <?php endif; ?>
                    <input type="hidden" name="existing_berkas_akta_lahir" value="<?php echo htmlspecialchars($student['berkas_akta_lahir']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="berkas_kk" class="form-label">Upload Scan Kartu Keluarga (KK)</label>
                    <input type="file" class="form-control" id="berkas_kk" name="berkas_kk">
                    <?php if($student['berkas_kk']): ?> <small class="form-text text-success">File sudah ada: <a href="uploads/<?php echo $student['berkas_kk']; ?>" target="_blank">Lihat</a></small> <?php endif; ?>
                    <input type="hidden" name="existing_berkas_kk" value="<?php echo htmlspecialchars($student['berkas_kk']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="berkas_skl" class="form-label">Upload Scan Surat Ket. Lulus (SKL)</label>
                    <input type="file" class="form-control" id="berkas_skl" name="berkas_skl">
                    <?php if($student['berkas_skl']): ?> <small class="form-text text-success">File sudah ada: <a href="uploads/<?php echo $student['berkas_skl']; ?>" target="_blank">Lihat</a></small> <?php endif; ?>
                    <input type="hidden" name="existing_berkas_skl" value="<?php echo htmlspecialchars($student['berkas_skl']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="berkas_surat_pernyataan" class="form-label">Upload Scan Surat Pernyataan</label>
                    <input type="file" class="form-control" id="berkas_surat_pernyataan" name="berkas_surat_pernyataan">
                    <?php if($student['berkas_surat_pernyataan']): ?> <small class="form-text text-success">File sudah ada: <a href="uploads/<?php echo $student['berkas_surat_pernyataan']; ?>" target="_blank">Lihat</a></small> <?php endif; ?>
                    <input type="hidden" name="existing_berkas_surat_pernyataan" value="<?php echo htmlspecialchars($student['berkas_surat_pernyataan']); ?>">
                </div>
            </div>

            <!-- Jalur Pendaftaran -->
            <h5 class="mt-4">Jalur Pendaftaran</h5>
            <hr>
            <div class="mb-3">
                <label class="form-label">Pilih Jalur</label>
                <select class="form-select" name="jalur_pendaftaran" id="jalur_pendaftaran" required>
                    <option value="PRESTASI_AKADEMIK" <?php echo ($student['jalur_pendaftaran'] == 'PRESTASI_AKADEMIK') ? 'selected' : ''; ?>>Prestasi Akademik</option>
                    <option value="PRESTASI_NON_AKADEMIK" <?php echo ($student['jalur_pendaftaran'] == 'PRESTASI_NON_AKADEMIK') ? 'selected' : ''; ?>>Prestasi Non-Akademik</option>
                    <option value="AFIRMASI" <?php echo ($student['jalur_pendaftaran'] == 'AFIRMASI') ? 'selected' : ''; ?>>Afirmasi</option>
                </select>
            </div>

            <!-- Form Dinamis -->
            <div id="form-dinamis">
                <!-- Prestasi Akademik -->
                <div class="mb-3" id="jalur_prestasi_akademik">
                    <label for="rata_rata_nilai_rapot" class="form-label">Rata-rata Nilai Rapot (5 Semester)</label>
                    <input type="number" step="0.01" class="form-control" id="rata_rata_nilai_rapot" name="rata_rata_nilai_rapot" value="<?php echo htmlspecialchars($student['rata_rata_nilai_rapot']); ?>">
                </div>
                <!-- Prestasi Non-Akademik -->
                <div class="mb-3" id="jalur_prestasi_non_akademik">
                    <label for="berkas_prestasi" class="form-label">Upload Scan Sertifikat/Piagam Prestasi</label>
                    <input type="file" class="form-control" id="berkas_prestasi" name="berkas_prestasi">
                    <?php if($student['berkas_prestasi']): ?> <small class="form-text text-success">File sudah ada: <a href="uploads/<?php echo $student['berkas_prestasi']; ?>" target="_blank">Lihat</a></small> <?php endif; ?>
                    <input type="hidden" name="existing_berkas_prestasi" value="<?php echo htmlspecialchars($student['berkas_prestasi']); ?>">
                </div>
                <!-- Afirmasi -->
                <div id="jalur_afirmasi">
                    <div class="mb-3">
                        <label for="no_kip" class="form-label">Nomor KIP (Kartu Indonesia Pintar)</label>
                        <input type="text" class="form-control" id="no_kip" name="no_kip" value="<?php echo htmlspecialchars($student['no_kip']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="berkas_kip" class="form-label">Upload Scan KIP</label>
                        <input type="file" class="form-control" id="berkas_kip" name="berkas_kip">
                        <?php if($student['berkas_kip']): ?> <small class="form-text text-success">File sudah ada: <a href="uploads/<?php echo $student['berkas_kip']; ?>" target="_blank">Lihat</a></small> <?php endif; ?>
                        <input type="hidden" name="existing_berkas_kip" value="<?php echo htmlspecialchars($student['berkas_kip']); ?>">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">Simpan dan Ajukan Data</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jalurPendaftaran = document.getElementById('jalur_pendaftaran');
    const formAkademik = document.getElementById('jalur_prestasi_akademik');
    const formNonAkademik = document.getElementById('jalur_prestasi_non_akademik');
    const formAfirmasi = document.getElementById('jalur_afirmasi');

    function toggleJalurForms() {
        const selectedJalur = jalurPendaftaran.value;
        formAkademik.style.display = (selectedJalur === 'PRESTASI_AKADEMIK') ? 'block' : 'none';
        formNonAkademik.style.display = (selectedJalur === 'PRESTASI_NON_AKADEMIK') ? 'block' : 'none';
        formAfirmasi.style.display = (selectedJalur === 'AFIRMASI') ? 'block' : 'none';
    }

    jalurPendaftaran.addEventListener('change', toggleJalurForms);
    toggleJalurForms(); // Initial call
});
</script>

<?php include 'templates/footer.php'; ?>