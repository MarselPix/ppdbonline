-- Database: `ppdbonline`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$examplehashedpassword$...'); -- Ganti dengan password hash Anda

-- --------------------------------------------------------

--
-- Table structure for table `calon_siswa`
--

CREATE TABLE `calon_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(20) NOT NULL,
  `jalur_pendaftaran` enum('PRESTASI_AKADEMIK','PRESTASI_NON_AKADEMIK','AFIRMASI') NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `asal_sekolah` varchar(255) DEFAULT NULL,
  `nama_ortu` varchar(255) DEFAULT NULL,
  `no_hp_ortu` varchar(20) DEFAULT NULL,
  `email_ortu` varchar(100) DEFAULT NULL,
  `rata_rata_nilai_rapot` decimal(5,2) DEFAULT NULL,
  `berkas_prestasi` varchar(255) DEFAULT NULL,
  `prestasi_tingkat` varchar(50) DEFAULT NULL,
  `prestasi_peringkat` varchar(50) DEFAULT NULL,
  `skor_prestasi` int(11) DEFAULT NULL,
  `no_kip` varchar(50) DEFAULT NULL,
  `berkas_kip` varchar(255) DEFAULT NULL,
  `status` enum('BARU_DAFTAR','PENGAJUAN','DITERIMA','DITOLAK') NOT NULL DEFAULT 'BARU_DAFTAR',
  `ranking` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_pendaftaran` (`no_pendaftaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_akademik` varchar(10) NOT NULL,
  `pendaftaran_buka` datetime NOT NULL,
  `pendaftaran_tutup` datetime NOT NULL,
  `pengumuman_tanggal` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `tahun_akademik`, `pendaftaran_buka`, `pendaftaran_tutup`, `pengumuman_tanggal`) VALUES
(1, '2024/2025', '2024-06-01 00:00:00', '2024-07-01 23:59:59', '2024-07-10 10:00:00');

