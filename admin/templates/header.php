<?php
// Ini adalah header untuk semua halaman di dalam panel admin
// Cek otentikasi di setiap halaman yang menggunakan header ini
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - PPDB Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .sidebar {
            width: 280px;
            background: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: #c2c7d0;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="d-flex w-100">
    <nav class="sidebar p-3 d-flex flex-column vh-100">
        <h4 class="text-center">Admin Panel</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="pendaftar.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pendaftar.php' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i> Manajemen Pendaftar
                </a>
            </li>
            <li>
                <a href="ranking.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'ranking.php' ? 'active' : ''; ?>">
                    <i class="bi bi-bar-chart-line"></i> Proses Perangkingan
                </a>
            </li>
            <li>
                <a href="seleksi.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'seleksi.php' ? 'active' : ''; ?>">
                    <i class="bi bi-check2-circle"></i> Penentuan Kelulusan
                </a>
            </li>
            <li>
                <a href="pengaturan.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pengaturan.php' ? 'active' : ''; ?>">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </li>
        </ul>
        <hr>
        <div>
            <a href="../logout.php" class="btn btn-danger w-100">Logout</a>
        </div>
    </nav>

    <main class="content bg-light">
