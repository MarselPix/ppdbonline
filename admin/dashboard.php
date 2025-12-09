<?php
// Menonaktifkan tampilan error di browser
error_reporting(0);
ini_set('display_errors', 0);
?>
<?php
session_start();
require '../config/database.php';
include 'templates/header.php';

// Ambil Statistik Cepat
$total_pendaftar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM calon_siswa"))['total'];
$total_diterima = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM calon_siswa WHERE status = 'DITERIMA'"))['total'];
$total_ditolak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM calon_siswa WHERE status = 'DITOLAK'"))['total'];
$total_pengajuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM calon_siswa WHERE status = 'PENGAJUAN'"))['total'];

// Ambil data untuk chart
$query_chart = "SELECT jalur_pendaftaran, COUNT(id) as jumlah FROM calon_siswa GROUP BY jalur_pendaftaran";
$result_chart = mysqli_query($conn, $query_chart);
$chart_labels = [];
$chart_data = [];
while($row = mysqli_fetch_assoc($result_chart)) {
    $chart_labels[] = str_replace('_', ' ', $row['jalur_pendaftaran']);
    $chart_data[] = $row['jumlah'];
}

?>

<h1 class="mb-4">Dashboard</h1>

<!-- Statistik Cepat -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <div>
                    <h3 class="mb-0"><?php echo $total_pendaftar; ?></h3>
                    <p class="text-muted mb-0">Total Pendaftar</p>
                </div>
                <div class="card-icon text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <div>
                    <h3 class="mb-0"><?php echo $total_diterima; ?></h3>
                    <p class="text-muted mb-0">Diterima</p>
                </div>
                <div class="card-icon text-success">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <div>
                    <h3 class="mb-0"><?php echo $total_ditolak; ?></h3>
                    <p class="text-muted mb-0">Ditolak</p>
                </div>
                <div class="card-icon text-danger">
                    <i class="bi bi-person-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <div>
                    <h3 class="mb-0"><?php echo $total_pengajuan; ?></h3>
                    <p class="text-muted mb-0">Menunggu Verifikasi</p>
                </div>
                <div class="card-icon text-warning">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Pendaftar -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Grafik Jumlah Pendaftar per Jalur
            </div>
            <div class="card-body">
                <canvas id="pendaftarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('pendaftarChart');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($chart_labels); ?>,
    datasets: [{
      label: '# Jumlah Pendaftar',
      data: <?php echo json_encode($chart_data); ?>,
      backgroundColor: [
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)'
      ],
      borderColor: [
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    responsive: true,
    maintainAspectRatio: false
  }
});
</script>

<?php include 'templates/footer.php'; ?>