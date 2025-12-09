<?php
error_reporting(0);
/*
 * File Konfigurasi Database
 * Ganti isian variabel di bawah ini sesuai dengan setting database Anda.
 */

$db_host = 'sql113.byethost33.com';     // Biasanya 'localhost'
$db_user = 'b33_40011306';          // User default XAMPP
$db_pass = 'mrslcn122009';              // Password default XAMPP kosong
$db_name = 'b33_40011306_ppdbonline';    // Nama database yang Anda buat

// Membuat koneksi ke database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (!$conn) {
    // Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error
    die("Koneksi ke database gagal. Silakan coba lagi nanti.");
}

// Set karakter set ke utf8mb4 untuk mendukung karakter yang lebih luas
mysqli_set_charset($conn, "utf8mb4");

?>