-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Bulan Mei 2026 pada 14.44
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cobit2019_bogakbesar`
--
CREATE DATABASE IF NOT EXISTS `cobit2019_bogakbesar` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cobit2019_bogakbesar`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `assessment_answers`
--

CREATE TABLE `assessment_answers` (
  `id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) UNSIGNED NOT NULL,
  `respondent_id` int(11) UNSIGNED NOT NULL,
  `assessment_date` date NOT NULL,
  `value` tinyint(3) UNSIGNED NOT NULL COMMENT '0-5 COBIT Capability Level',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answered_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `assessment_questions`
--

CREATE TABLE `assessment_questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `process_id` int(11) UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `practice_reference` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `assessment_questions`
--

INSERT INTO `assessment_questions` (`id`, `process_id`, `question`, `practice_reference`, `created_at`) VALUES
(1, 1, 'Prosedur kerja digunakan dalam menjalankan sistem', 'DSS01.01', '2026-03-13 23:30:44'),
(2, 1, 'Langkah penggunaan sistem sudah jelas', 'DSS01.01', '2026-03-13 23:30:44'),
(3, 1, 'Operator memahami penggunaan sistem dengan baik', 'DSS01.02', '2026-03-13 23:30:44'),
(4, 1, 'Internet mendukung pekerjaan dengan baik', 'DSS01.02', '2026-03-13 23:30:44'),
(5, 1, 'Penanganan gangguan jaringan dilakukan dengan baik', 'DSS01.03', '2026-03-13 23:30:44'),
(6, 1, 'Pekerjaan tetap berjalan saat operator berbeda', 'DSS01.03', '2026-03-13 23:30:44'),
(7, 1, 'Operator memahami penggunaan sistem dengan baik', 'DSS01.04', '2026-03-13 23:30:44'),
(8, 1, 'Dukungan teknisi tersedia saat terjadi masalah', 'DSS01.04', '2026-03-13 23:30:44'),
(9, 1, 'Penanganan gangguan dari pihak luar dilakukan dengan baik', 'DSS01.06', '2026-03-13 23:30:44'),
(10, 1, 'Pemeriksaan perangkat dilakukan secara rutin', 'DSS01.06', '2026-03-13 23:30:44'),
(11, 2, 'Identifikasi masalah dilakukan dengan jelas', 'DSS05.01', '2026-03-13 23:30:44'),
(12, 2, 'Penentuan prioritas masalah dilakukan dengan baik', 'DSS05.01', '2026-03-13 23:30:44'),
(13, 2, 'Pemahaman terhadap gangguan oleh pegawai dilakukan dengan baik', 'DSS05.02', '2026-03-13 23:30:44'),
(14, 2, 'Masalah yang terjadi dicatat', 'DSS05.02', '2026-03-13 23:30:44'),
(15, 2, 'Penyimpanan Riwayat gangguan disimpan dengan rapi', 'DSS05.03', '2026-03-13 23:30:44'),
(16, 2, 'Permintaan bantuan didokumentasikan/Dicatat', 'DSS05.03', '2026-03-13 23:30:44'),
(17, 2, 'Penanggung jawab dalam penanganan masalah telah ditetapkan', 'DSS05.04', '2026-03-13 23:30:44'),
(18, 2, 'Pemeliharaan perangkat keras dilakukan secara berkala', 'DSS05.04', '2026-03-13 23:30:44'),
(19, 2, 'Pemeliharaan sistem atau aplikasi dilakukan secara berkala', 'DSS05.05', '2026-03-13 23:30:44'),
(20, 2, 'Penanganan masalah dilakukan oleh pihak yang sesuai', 'DSS05.06', '2026-03-13 23:30:44');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `assessment_summary`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `assessment_summary` (
`process_id` int(11) unsigned
,`process_code` varchar(10)
,`process_name` varchar(100)
,`total_questions` bigint(21)
,`answered_questions` bigint(21)
,`avg_capability_level` decimal(6,2)
,`gap_from_target` decimal(7,2)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `design_factors`
--

CREATE TABLE `design_factors` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `processes`
--

CREATE TABLE `processes` (
  `id` int(11) UNSIGNED NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `processes`
--

INSERT INTO `processes` (`id`, `code`, `name`, `description`, `created_at`) VALUES
(1, 'DSS01', 'Manage Operations', 'Mengelola operasional TI untuk memastikan kelangsungan layanan teknologi informasi secara efektif dan efisien dalam Pengelolaan Layanan Desa.', '2026-03-13 23:30:43'),
(2, 'DSS02', 'Manage Service Requests and Incidents', 'Berfokus pada penanganan layanan dan insiden yang terjadi pada sistem.', '2026-03-13 23:30:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `respondents`
--

CREATE TABLE `respondents` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('operator_sistem','perangkat_desa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `respondents`
--

INSERT INTO `respondents` (`id`, `name`, `position`, `category`, `created_at`) VALUES
(1, 'Responden 1', 'Operator Sistem', 'operator_sistem', '2026-05-16 07:39:28'),
(2, 'Responden 2', 'Perangkat Desa', 'perangkat_desa', '2026-05-16 07:39:28'),
(3, 'Responden 3', 'Perangkat Desa', 'perangkat_desa', '2026-05-16 07:39:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `results`
--

CREATE TABLE `results` (
  `id` int(11) UNSIGNED NOT NULL,
  `process_id` int(11) UNSIGNED NOT NULL,
  `capability_level` decimal(3,2) DEFAULT 0.00 COMMENT 'Calculated capability level 0.00-5.00',
  `target_level` decimal(3,2) DEFAULT 3.00 COMMENT 'Target capability level',
  `gap` decimal(3,2) DEFAULT 3.00 COMMENT 'Difference between target and current',
  `recommendation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of recommendations' CHECK (json_valid(`recommendation`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `results`
--

INSERT INTO `results` (`id`, `process_id`, `capability_level`, `target_level`, `gap`, `recommendation`, `created_at`, `updated_at`) VALUES
(1, 1, '0.00', '3.00', '3.00', '{\"gap\":3,\"level\":\"Major Gap\",\"color\":\"danger\",\"recommendations\":[\"Lakukan transformasi proses fundamental\",\"Susun roadmap perbaikan jangka panjang\",\"Pertimbangkan outsourcing untuk kompetensi yang kurang\",\"Lakukan pelatihan intensif untuk seluruh tim\",\"Implementasi framework governance yang komprehensif\",\"Evaluasi ulang struktur organisasi IT\"]}', '2026-05-17 12:41:19', '2026-05-17 12:41:19');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `unanswered_questions`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `unanswered_questions` (
`question_id` int(11) unsigned
,`question` text
,`process_code` varchar(10)
,`process_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@cobit.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-03-13 23:30:43', '2026-03-13 23:30:43');

-- --------------------------------------------------------

--
-- Struktur untuk view `assessment_summary`
--
DROP TABLE IF EXISTS `assessment_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `assessment_summary`  AS  select `p`.`id` AS `process_id`,`p`.`code` AS `process_code`,`p`.`name` AS `process_name`,count(distinct `aq`.`id`) AS `total_questions`,count(distinct `aa`.`question_id`) AS `answered_questions`,round(avg(`aa`.`value`),2) AS `avg_capability_level`,round(4 - avg(`aa`.`value`),2) AS `gap_from_target` from ((`processes` `p` left join `assessment_questions` `aq` on(`p`.`id` = `aq`.`process_id`)) left join `assessment_answers` `aa` on(`aq`.`id` = `aa`.`question_id`)) group by `p`.`id`,`p`.`code`,`p`.`name` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `unanswered_questions`
--
DROP TABLE IF EXISTS `unanswered_questions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `unanswered_questions`  AS  select `aq`.`id` AS `question_id`,`aq`.`question` AS `question`,`p`.`code` AS `process_code`,`p`.`name` AS `process_name` from ((`assessment_questions` `aq` join `processes` `p` on(`aq`.`process_id` = `p`.`id`)) left join `assessment_answers` `aa` on(`aq`.`id` = `aa`.`question_id`)) where `aa`.`id` is null ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `assessment_answers`
--
ALTER TABLE `assessment_answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_answer` (`question_id`,`respondent_id`,`assessment_date`),
  ADD KEY `answered_by` (`answered_by`),
  ADD KEY `idx_question` (`question_id`),
  ADD KEY `idx_respondent` (`respondent_id`);

--
-- Indeks untuk tabel `assessment_questions`
--
ALTER TABLE `assessment_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_process` (`process_id`);

--
-- Indeks untuk tabel `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`);

--
-- Indeks untuk tabel `respondents`
--
ALTER TABLE `respondents`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_process_result` (`process_id`),
  ADD KEY `idx_process` (`process_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `assessment_answers`
--
ALTER TABLE `assessment_answers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `assessment_questions`
--
ALTER TABLE `assessment_questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `processes`
--
ALTER TABLE `processes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `respondents`
--
ALTER TABLE `respondents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `assessment_answers`
--
ALTER TABLE `assessment_answers`
  ADD CONSTRAINT `assessment_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `assessment_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessment_answers_ibfk_2` FOREIGN KEY (`answered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_assessment_answers_respondents` FOREIGN KEY (`respondent_id`) REFERENCES `respondents` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `assessment_questions`
--
ALTER TABLE `assessment_questions`
  ADD CONSTRAINT `assessment_questions_ibfk_1` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
