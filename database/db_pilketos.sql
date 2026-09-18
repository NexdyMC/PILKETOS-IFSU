-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 18, 2026 at 07:17 AM
-- Server version: 8.0.30
-- PHP Version: 8.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pilketos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_14_000001_create_tb_admin_table', 2),
(5, '2026_09_14_000002_create_tb_kandidat_table', 2),
(6, '2026_09_14_000003_create_tb_settings_table', 2),
(7, '2026_09_14_000004_create_tb_siswa_table', 2),
(8, '2026_09_14_000005_update_admin_password_rehash', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mg5AJc0485GBsvWDaya00j6skh3zjfbBZsIBBXb8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI3RnQyMEdqQWFseU5UUFVZY2pIMjFTaTdieW5vYk9GM1h1ZGVRNVJVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4ODAwXC9hZG1pblwvZGFzaGJvYXJkIiwicm91dGUiOiJhZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJhZG1pbl9pZCI6bnVsbCwidXNlcm5hbWUiOiJGZWJyaSBQcmF0YW1hIn0=', 1789617581);

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `admin` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `admin`, `kelas`, `password`, `username`) VALUES
(10, 'Febri Pratama', '12 RPL 1', '$2y$12$msBf3PKxKu.AW0peLDHGUOU6JYHXj3oFrgXNSGfioS8uaq4z/wyue', 'Febri Pratama');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kandidat`
--

CREATE TABLE `tb_kandidat` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) CHARACTER SET utf8mb4 NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `image` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_kandidat`
--

INSERT INTO `tb_kandidat` (`id`, `nama`, `kelas`, `visi`, `misi`, `image`) VALUES
(69, 'Hoshino', '12 RPL 1', 'Menjadikan OSIS sebagai organisasi yang menjadi wadah aspirasi warga SMK Informatika Sumedang, serta mengembangkan potensi siswa agar aktif, kreatif dan berani berpendapat.', '1. Mengembangkan siswa siswi SMK Informatika Sumedang untuk lebih aktif dalam bersosialisasi.\r\n2. Meningkatkan minat literasi dikalangan siswa.\r\n3. Membentuk lingkungan sekolah yang peduli terhadap kebersihan dan kesehatan.\r\n4. Membangun keterampilan dan pengetahuan siswa.', 'kandidat_6aa3bc0e46b28.jpg'),
(70, 'Shiroko', '12 RPL 1', 'Mewujudkan OSIS SMK Informatika Sumedang yang unggul dalam prestasi, solid dalam kebersamaan, dan hebat dalam karya menuju generasi yang berkarakter, berdaya saing, dan berpengaruh positif bagi sekolah.', '1. Membangun OSIS yang aktif, inspiratif, dan meningkatkan kualitas diri dari pengurus osis yang menjadi contoh nyata bagi seluruh siswa\r\n2. Menciptakan lingkungan sekolah yang nyaman, kreatif, dan penuh semangat kolaborasi.\r\n3. Mengembangkan potensi setiap siswa melalui kegiatan inovatif dan berbasis teknologi.\r\n4. Menumbuhkan semangat peduli, disiplin, dan bertanggung jawab dalam setiap tindakan.\r\n5. Menjadikan OSIS sebagai wadah aspirasi, tempat berkembang, tempat semua suara di dengar dan ruang perubahan.', 'kandidat_6aa3ba9301bb2.jpg'),
(71, 'Aris', '12 RPL 1', 'Mewujudkan OSIS SMK Informatika Sumedang yang unggul dalam prestasi, solid dalam kebersamaan, dan hebat dalam karya menuju generasi yang berkarakter, berdaya saing, dan berpengaruh positif bagi sekolah.', '1. Membangun OSIS yang aktif, inspiratif, dan meningkatkan kualitas diri dari pengurus osis yang menjadi contoh nyata bagi seluruh siswa\r\n2. Menciptakan lingkungan sekolah yang nyaman, kreatif, dan penuh semangat kolaborasi.\r\n3. Mengembangkan potensi setiap siswa melalui kegiatan inovatif dan berbasis teknologi.\r\n4. Menumbuhkan semangat peduli, disiplin, dan bertanggung jawab dalam setiap tindakan.\r\n5. Menjadikan OSIS sebagai wadah aspirasi, tempat berkembang, tempat semua suara di dengar dan ruang perubahan.', 'kandidat_6aa3ba7ccde14.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_settings`
--

CREATE TABLE `tb_settings` (
  `id` int NOT NULL,
  `nama_sekolah` varchar(40) NOT NULL,
  `judul_pemilihan` varchar(30) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `status_voting` enum('0','1') NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `logo_sekolah` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_settings`
--

INSERT INTO `tb_settings` (`id`, `nama_sekolah`, `judul_pemilihan`, `tahun_ajaran`, `status_voting`, `waktu_mulai`, `waktu_selesai`, `logo_sekolah`) VALUES
(836197, 'SMK Informatika Sumedang', 'E-Vote OSIS', '2026 - 2027', '0', '2026-10-05 12:22:00', '2026-11-30 12:22:00', 'logo_1786966071.png');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `token` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `voted` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`token`, `nama`, `kelas`, `status`, `voted`) VALUES
('0206', 'Nexdy experiment', '11 RPL 1', 0, 0),
('090X', 'Takanashi Hoshino', '12 RPL 1', 1, 70),
('25D3', 'Nexdy experiment', '11 RPL 1', 1, 71),
('7O14', 'Ramdhani', '12 RPL 1', 1, 69),
('89P7', 'Nexdy experiment', '11 RPL 1', 0, 0),
('ABCD', 'febri pratama', '11 RPL 1', 1, 69),
('DFJK', 'muhammad ramdhani', '11 RPL 1', 0, 0),
('F9RW', 'Ridwan Saepuloh', '12 RPL 1', 1, 69),
('NX74', 'Aditya Anugrah', '12 RPL 1', 1, 69),
('NXYZ', 'Nexdy experiment', '11 RPL 1', 1, 70),
('ONBE', 'Nexdy experiment', '11 RPL 1', 0, 0),
('OPQR', 'fahri nasluroh', '11 RPL 1', 1, 69),
('TZ11', 'Muhammad Syarif Badrudin', '12 RPL 2', 0, 0),
('TZ8B', 'Nexdy experiment', '11 RPL 1', 0, 0),
('U4S4', 'Hafidz', '11 RPL 1', 1, 69),
('X8O5', 'Nexdy experiment', '11 RPL 1', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tb_kandidat`
--
ALTER TABLE `tb_kandidat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_settings`
--
ALTER TABLE `tb_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tb_kandidat`
--
ALTER TABLE `tb_kandidat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `tb_settings`
--
ALTER TABLE `tb_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=836198;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
