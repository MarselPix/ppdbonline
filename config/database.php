<?php
/*
 * File Konfigurasi Database
 * Ganti isian variabel di bawah ini sesuai dengan setting database Anda.
 */

$db_host = 'localhost';     // Biasanya 'localhost'
$db_user = 'root';          // User default XAMPP
$db_pass = '';              // Password default XAMPP kosong
$db_name = 'ppdbonline';    // Nama database yang Anda buat

// Membuat koneksi ke database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (!$conn) {
    // Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Set karakter set ke utf8mb4 untuk mendukung karakter yang lebih luas
mysqli_set_charset($conn, "utf8mb4");

?>