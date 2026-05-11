-- ========================================================
-- Database: cobit2019_bogakbesar
-- Sistem Analisis Pengelolaan Layanan Teknologi Informasi
-- berbasis COBIT 2019 Domain DSS
-- untuk Kantor Desa Bogak Besar
-- Peneliti : Sandy Donny Tampubolon (220141012)
-- STMIK Pelita Nusantara, 2026
-- ========================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `cobit2019_bogakbesar`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `cobit2019_bogakbesar`;

-- ========================================================
-- Table: users
-- ========================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Password: password (hashed with password_hash)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Administrator', 'admin@cobit.com', '$2a$12$IDVisdvogTvdeuWZDfYyquRFrtniLEf0saCbLt/6O3ealB6tmea.a', 'admin');

-- ========================================================
-- Table: processes
-- Domain yang digunakan: DSS01 dan DSS02 (Tabel 4.2 Proses Domain DSS)
-- ========================================================
DROP TABLE IF EXISTS `processes`;
CREATE TABLE `processes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert COBIT Processes (DSS01 & DSS02) sesuai Tabel 4.2 Proses Domain DSS
INSERT INTO `processes` (`code`, `name`, `description`) VALUES
('DSS01', 'Manage Operations',
 'Proses yang berkaitan dengan kegiatan operasional layanan TI sehari-hari di Kantor Desa Bogak Besar yang masih memerlukan peningkatan. Fokus evaluasi meliputi prosedur operasional, pengelolaan infrastruktur TI, lingkungan operasional, dan pemeliharaan perangkat.'),
('DSS02', 'Manage Service Requests and Incidents',
 'Proses yang berfokus pada penanganan permintaan layanan dan insiden yang belum terstruktur di Kantor Desa Bogak Besar. Fokus evaluasi meliputi klasifikasi masalah, pencatatan insiden, analisis masalah, penyelesaian insiden, dan monitoring layanan.');

-- ========================================================
-- Table: design_factors
-- Design Factor yang dipilih sesuai Tabel 4.3 Design Factor
-- Dipilih: DF02, DF03, DF04, DF07, DF11
-- ========================================================
DROP TABLE IF EXISTS `design_factors`;
CREATE TABLE `design_factors` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(10) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `is_selected` TINYINT(1) DEFAULT 0 COMMENT '1 = dipilih, 0 = tidak dipilih',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert seluruh Design Factor COBIT 2019 (DF1-DF11) sesuai Tabel 4.3
-- yang dipilih (is_selected=1): DF02, DF03, DF04, DF07, DF11
INSERT INTO `design_factors` (`code`, `name`, `description`, `is_selected`) VALUES
('DF01', 'Enterprise Strategy',
 'Strategi organisasi dalam mencapai tujuan bisnis.', 0),
('DF02', 'Enterprise Goals',
 'Tujuan utama Kantor Desa Bogak Besar adalah meningkatkan kualitas pelayanan kepada masyarakat serta mendukung kelancaran aktivitas kerja pegawai. Untuk mencapai tujuan tersebut, diperlukan dukungan teknologi informasi yang berjalan baik agar proses pelayanan dan pekerjaan dapat dilakukan secara efektif.', 1),
('DF03', 'Risk Profile',
 'Terdapat beberapa risiko yang dapat menghambat pelayanan dan pekerjaan pegawai, seperti komputer mengalami gangguan, jaringan internet tidak stabil, printer rusak, serta keterlambatan dalam penyelesaian pekerjaan. Risiko tersebut dapat mempengaruhi kelancaran layanan yang diberikan.', 1),
('DF04', 'I&T Related Issues',
 'Permasalahan yang ditemukan berkaitan dengan teknologi informasi, seperti perangkat komputer yang lambat, printer mengalami kendala, serta penanganan masalah yang masih dilakukan ketika gangguan terjadi. Kondisi ini menunjukkan bahwa pengelolaan TI masih perlu ditingkatkan.', 1),
('DF05', 'Threat Landscape',
 'Ancaman eksternal terhadap sistem dan teknologi.', 0),
('DF06', 'Compliance Requirements',
 'Kebutuhan kepatuhan terhadap regulasi dan standar.', 0),
('DF07', 'Role of IT',
 'Teknologi informasi digunakan untuk mendukung aktivitas kerja di kantor desa, seperti pengolahan data, pembuatan dokumen, penyimpanan arsip, dan pencetakan berkas. Hal ini menunjukkan bahwa TI memiliki peran penting dalam menunjang operasional kantor sehari-hari.', 1),
('DF08', 'Sourcing Model for IT',
 'Model pengelolaan sumber daya TI (internal/eksternal).', 0),
('DF09', 'IT Implementation Methods',
 'Metode implementasi teknologi yang digunakan.', 0),
('DF10', 'Technology Adoption Strategy',
 'Strategi dalam mengadopsi teknologi baru.', 0),
('DF11', 'Enterprise Size',
 'Kantor Desa Bogak Besar memiliki jumlah pegawai yang relatif terbatas dengan struktur organisasi yang sederhana. Kondisi ini menyebabkan pengelolaan teknologi informasi dilakukan sesuai sumber daya yang tersedia dan belum memiliki petugas khusus di bidang TI.', 1);

-- ========================================================
-- Table: assessment_questions
-- Masing-masing domain (DSS01 dan DSS02) memiliki 16 pertanyaan
-- sesuai perhitungan skripsi: 16 pertanyaan x 3 responden = 48 (pembagi)
-- Sub-proses DSS01: DSS01.01 – DSS01.05 (Tabel 3.2)
-- Sub-proses DSS02: DSS02.01 – DSS02.07 (Tabel 3.3)
-- ========================================================
DROP TABLE IF EXISTS `assessment_questions`;
CREATE TABLE `assessment_questions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `process_id` INT(11) UNSIGNED NOT NULL,
    `question` TEXT NOT NULL,
    `practice_reference` VARCHAR(20) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`process_id`) REFERENCES `processes`(`id`) ON DELETE CASCADE,
    INDEX `idx_process` (`process_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Pertanyaan untuk DSS01 - Manage Operations (16 pertanyaan)
-- Sub-proses: DSS01.01 Perform Operational Procedures
--             DSS01.02 Manage Outsourced IT Services
--             DSS01.03 Monitor IT Infrastructure
--             DSS01.04 Manage the Environment
--             DSS01.05 Manage Facilities
-- --------------------------------------------------------
INSERT INTO `assessment_questions` (`process_id`, `question`, `practice_reference`) VALUES
-- DSS01.01 – Perform Operational Procedures (4 pertanyaan)
(1, 'Apakah terdapat prosedur operasional standar (SOP) tertulis untuk pengelolaan layanan TI di Kantor Desa Bogak Besar?', 'DSS01.01'),
(1, 'Apakah aktivitas operasional harian seperti penggunaan komputer, printer, dan jaringan sudah terjadwal dengan baik?', 'DSS01.01'),
(1, 'Apakah operator melaksanakan tugas operasional sesuai dengan prosedur yang telah ditetapkan?', 'DSS01.01'),
(1, 'Apakah pembagian tugas dan tanggung jawab dalam operasional TI sudah jelas dan dipahami oleh seluruh operator?', 'DSS01.01'),
-- DSS01.02 – Manage Outsourced IT Services (2 pertanyaan)
(1, 'Apakah terdapat koordinasi dengan pihak eksternal (vendor atau teknisi) untuk pemeliharaan perangkat TI di kantor desa?', 'DSS01.02'),
(1, 'Apakah layanan dari pihak ketiga atau vendor TI yang digunakan kantor desa dikelola dan dievaluasi secara berkala?', 'DSS01.02'),
-- DSS01.03 – Monitor IT Infrastructure (3 pertanyaan)
(1, 'Apakah dilakukan pemantauan kinerja perangkat TI (komputer, printer, jaringan) secara rutin di Kantor Desa Bogak Besar?', 'DSS01.03'),
(1, 'Apakah terdapat pencatatan atau laporan kondisi infrastruktur TI yang digunakan di kantor desa?', 'DSS01.03'),
(1, 'Apakah kinerja jaringan internet di kantor desa dipantau untuk memastikan kestabilan koneksi dalam mendukung operasional?', 'DSS01.03'),
-- DSS01.04 – Manage the Environment (3 pertanyaan)
(1, 'Apakah lingkungan fisik tempat perangkat TI ditempatkan (ruangan, suhu, kebersihan) dikelola dengan baik?', 'DSS01.04'),
(1, 'Apakah terdapat langkah-langkah untuk melindungi perangkat TI dari kerusakan akibat faktor lingkungan (debu, panas, kelembapan)?', 'DSS01.04'),
(1, 'Apakah keamanan fisik perangkat TI di kantor desa terjaga dari potensi kehilangan atau kerusakan yang disengaja?', 'DSS01.04'),
-- DSS01.05 – Manage Facilities (4 pertanyaan)
(1, 'Apakah fasilitas pendukung TI seperti sumber listrik dan stabilizer atau UPS tersedia dan berfungsi dengan baik?', 'DSS01.05'),
(1, 'Apakah terdapat jadwal pemeliharaan berkala untuk perangkat keras dan fasilitas pendukung TI di kantor desa?', 'DSS01.05'),
(1, 'Apakah inventaris perangkat TI (komputer, printer, dan aksesori) di kantor desa terdokumentasi secara lengkap?', 'DSS01.05'),
(1, 'Apakah kondisi dan usia perangkat TI dipantau secara berkala untuk menentukan kebutuhan penggantian atau perbaikan?', 'DSS01.05');

-- --------------------------------------------------------
-- Pertanyaan untuk DSS02 - Manage Service Requests and Incidents (16 pertanyaan)
-- Sub-proses: DSS02.01 Define Incident and Service Request Classification Scheme
--             DSS02.02 Record, Classify and Prioritise Requests and Incidents
--             DSS02.03 Verify, Approve and Fulfil Service Requests
--             DSS02.04 Investigate, Diagnose and Allocate Incidents
--             DSS02.05 Resolve and Recover from Incidents
--             DSS02.06 Close Service Requests and Incidents
--             DSS02.07 Track Status and Produce Reports
-- --------------------------------------------------------
INSERT INTO `assessment_questions` (`process_id`, `question`, `practice_reference`) VALUES
-- DSS02.01 – Define Incident and Service Request Classification Scheme (2 pertanyaan)
(2, 'Apakah terdapat kategori atau klasifikasi jenis gangguan dan permintaan layanan TI yang digunakan di Kantor Desa Bogak Besar?', 'DSS02.01'),
(2, 'Apakah terdapat penetapan prioritas penanganan berdasarkan tingkat dampak gangguan terhadap operasional kantor desa?', 'DSS02.01'),
-- DSS02.02 – Record, Classify and Prioritise Requests and Incidents (3 pertanyaan)
(2, 'Apakah setiap gangguan atau permintaan layanan TI yang terjadi dicatat secara tertulis atau dalam sistem pencatatan?', 'DSS02.02'),
(2, 'Apakah laporan gangguan atau permintaan layanan dikelompokkan berdasarkan jenis dan tingkat urgensinya?', 'DSS02.02'),
(2, 'Apakah terdapat formulir atau media khusus yang digunakan untuk melaporkan gangguan atau permintaan layanan TI?', 'DSS02.02'),
-- DSS02.03 – Verify, Approve and Fulfil Service Requests (2 pertanyaan)
(2, 'Apakah permintaan layanan TI dari pengguna (misalnya perbaikan printer atau penambahan akses) diverifikasi sebelum ditindaklanjuti?', 'DSS02.03'),
(2, 'Apakah terdapat prosedur untuk memastikan bahwa permintaan layanan diselesaikan sesuai dengan kebutuhan pengguna?', 'DSS02.03'),
-- DSS02.04 – Investigate, Diagnose and Allocate Incidents (3 pertanyaan)
(2, 'Apakah dilakukan identifikasi dan investigasi terhadap penyebab terjadinya gangguan pada perangkat atau sistem TI di kantor desa?', 'DSS02.04'),
(2, 'Apakah terdapat penetapan penanggung jawab yang jelas untuk menangani setiap gangguan atau insiden yang terjadi?', 'DSS02.04'),
(2, 'Apakah masalah teknis yang kompleks dieskalasi kepada pihak yang lebih berwenang atau teknisi ahli dari luar kantor desa?', 'DSS02.04'),
-- DSS02.05 – Resolve and Recover from Incidents (2 pertanyaan)
(2, 'Apakah terdapat prosedur untuk menyelesaikan gangguan dan memulihkan layanan TI setelah terjadi insiden?', 'DSS02.05'),
(2, 'Apakah penyelesaian gangguan dilakukan dalam waktu yang dapat diterima sehingga tidak mengganggu operasional kantor secara berkepanjangan?', 'DSS02.05'),
-- DSS02.06 – Close Service Requests and Incidents (2 pertanyaan)
(2, 'Apakah setiap gangguan atau permintaan layanan yang telah diselesaikan ditutup secara resmi dan dicatat hasilnya?', 'DSS02.06'),
(2, 'Apakah dilakukan konfirmasi kepada pengguna bahwa gangguan atau permintaan layanan telah diselesaikan dengan baik?', 'DSS02.06'),
-- DSS02.07 – Track Status and Produce Reports (2 pertanyaan)
(2, 'Apakah terdapat pemantauan terhadap status penanganan gangguan dan permintaan layanan yang sedang berjalan?', 'DSS02.07'),
(2, 'Apakah dibuat laporan atau rekap berkala mengenai gangguan yang terjadi dan penanganannya di Kantor Desa Bogak Besar?', 'DSS02.07');

-- ========================================================
-- Table: respondents
-- Identifikasi responden sesuai Bagian 4.2.1
-- Total 3 responden
-- ========================================================
DROP TABLE IF EXISTS `respondents`;
CREATE TABLE `respondents` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `position` VARCHAR(100) NOT NULL COMMENT 'Jabatan/peran responden di kantor desa',
    `category` ENUM('operator_sistem', 'perangkat_desa') NOT NULL
        COMMENT 'operator_sistem = operator yg mengelola TI; perangkat_desa = pengguna sistem administrasi',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `respondents` (`name`, `position`, `category`) VALUES
('Responden 1', 'Operator Sistem 1', 'operator_sistem'),
('Responden 2', 'Operator Sistem 2', 'operator_sistem'),
('Responden 3', 'Perangkat Desa', 'perangkat_desa');

-- ========================================================
-- Table: assessment_answers
-- Skala penilaian 0-5 sesuai Process Capability Model COBIT 2019
-- Rumus capability: Total nilai / (16 pertanyaan x 3 responden) = Total / 48
-- ========================================================
DROP TABLE IF EXISTS `assessment_answers`;
CREATE TABLE `assessment_answers` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `question_id` INT(11) UNSIGNED NOT NULL,
    `respondent_id` INT(11) UNSIGNED NOT NULL
        COMMENT 'Referensi ke tabel respondents',
    `value` TINYINT UNSIGNED NOT NULL COMMENT '0-5 COBIT Capability Level',
    `notes` TEXT,
    `answered_by` INT(11) UNSIGNED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`question_id`) REFERENCES `assessment_questions`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`respondent_id`) REFERENCES `respondents`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`answered_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    UNIQUE KEY `unique_answer` (`question_id`, `respondent_id`)
        COMMENT 'Satu jawaban per pertanyaan per responden',
    INDEX `idx_question` (`question_id`),
    INDEX `idx_respondent` (`respondent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- Table: results
-- Target Level = 4 (Level 4 Predictable) sesuai Tabel 4.6
-- DSS01: current=2.96, target=4, gap=1.04
-- DSS02: current=1.81, target=4, gap=2.19
-- ========================================================
DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `process_id` INT(11) UNSIGNED NOT NULL,
    `capability_level` DECIMAL(4,2) DEFAULT 0.00
        COMMENT 'Nilai capability level hasil perhitungan (0.00-5.00)',
    `capability_percentage` DECIMAL(5,2) DEFAULT 0.00
        COMMENT 'Persentase pencapaian: (total nilai / (16 x 3 x 5)) x 100',
    `rating` VARCHAR(1) DEFAULT NULL
        COMMENT 'Rating singkat (L=Low, M=Medium, H=High)',
    `target_level` DECIMAL(4,2) DEFAULT 4.00
        COMMENT 'Target capability level = Level 4 (Predictable)',
    `gap` DECIMAL(4,2) DEFAULT 4.00
        COMMENT 'Selisih target dengan nilai saat ini',
    `as_is_description` TEXT
        COMMENT 'Deskripsi kondisi saat ini (As-Is)',
    `to_be_description` TEXT
        COMMENT 'Deskripsi kondisi yang diharapkan (To-Be)',
    `gap_interpretation` VARCHAR(50) DEFAULT NULL
        COMMENT 'Interpretasi gap: Optimal / Minor Gap / Moderate Gap / Major Gap',
    `recommendation` JSON COMMENT 'JSON array berisi rekomendasi perbaikan',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`process_id`) REFERENCES `processes`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_process_result` (`process_id`),
    INDEX `idx_process` (`process_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed hasil awal sesuai Tabel 4.11, 4.12, 4.15, 4.16 pada skripsi
INSERT INTO `results`
    (`process_id`, `capability_level`, `capability_percentage`, `rating`,
     `target_level`, `gap`, `as_is_description`, `to_be_description`,
     `gap_interpretation`, `recommendation`)
VALUES
(1, 2.96, 59.17, 'L', 4.00, 1.04,
 'Sistem sudah digunakan dalam mendukung operasional, namun belum terdapat pemantauan kinerja secara rutin dan dokumentasi belum konsisten.',
 'Proses operasional berjalan secara terukur, dipantau secara rutin, dan didukung dokumentasi yang lengkap.',
 'Minor Gap',
 JSON_ARRAY(
     JSON_OBJECT('fokus', 'Monitoring',    'perbaikan', 'Melakukan pemantauan sistem secara berkala menggunakan indikator kinerja yang terukur'),
     JSON_OBJECT('fokus', 'Pengukuran',   'perbaikan', 'Menentukan indikator kinerja (KPI) sistem TI agar kinerja dapat dievaluasi secara objektif'),
     JSON_OBJECT('fokus', 'Dokumentasi',  'perbaikan', 'Melengkapi prosedur operasional standar (SOP) secara tertulis dan mensosialisasikannya ke seluruh operator'),
     JSON_OBJECT('fokus', 'Pemeliharaan', 'perbaikan', 'Menyusun jadwal perawatan perangkat TI secara berkala dan mencatat hasilnya')
 )),
(2, 1.81, 36.25, 'P', 4.00, 2.19,
 'Penanganan gangguan sudah dilakukan, namun belum memiliki prosedur yang jelas, pencatatan belum terstruktur, dan pembagian tugas belum jelas dan pasti.',
 'Penanganan insiden dilakukan berdasarkan prosedur yang jelas, terdokumentasi, serta memiliki pembagian tugas yang terstruktur.',
 'Moderate Gap',
 JSON_ARRAY(
     JSON_OBJECT('fokus', 'SOP',                   'perbaikan', 'Menyusun prosedur penanganan masalah dan permintaan layanan TI secara tertulis'),
     JSON_OBJECT('fokus', 'Pembagian Tugas',        'perbaikan', 'Menentukan tanggung jawab setiap petugas dalam penanganan insiden agar tidak terjadi kebingungan'),
     JSON_OBJECT('fokus', 'Dokumentasi',            'perbaikan', 'Mencatat setiap gangguan yang terjadi beserta cara penyelesaiannya untuk mencegah masalah berulang'),
     JSON_OBJECT('fokus', 'Pemeliharaan',           'perbaikan', 'Menjadwalkan perawatan infrastruktur TI secara preventif agar gangguan dapat diminimalkan'),
     JSON_OBJECT('fokus', 'Monitoring',             'perbaikan', 'Melakukan pemantauan proses layanan dan membuat laporan berkala tentang insiden yang terjadi')
 ));

-- ========================================================
-- Views for Reporting
-- ========================================================

-- View: assessment_summary
-- Rumus capability: SUM(nilai) / (16 pertanyaan x 3 responden)
-- Rumus persentase: SUM(nilai) / (16 x 3 x 5) x 100
DROP VIEW IF EXISTS `assessment_summary`;
CREATE VIEW `assessment_summary` AS
SELECT
    p.id                                                       AS process_id,
    p.code                                                     AS process_code,
    p.name                                                     AS process_name,
    COUNT(DISTINCT aq.id)                                      AS total_questions,
    COUNT(DISTINCT aa.id)                                      AS total_answers,
    COUNT(DISTINCT aa.respondent_id)                           AS total_respondents,
    SUM(aa.value)                                              AS total_nilai,
    ROUND(SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3), 2)     AS capability_level,
    ROUND(SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3 * 5) * 100, 2) AS capability_percentage,
    4.00                                                       AS target_level,
    ROUND(4 - (SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3)), 2) AS gap_from_target
FROM processes p
LEFT JOIN assessment_questions aq ON p.id = aq.process_id
LEFT JOIN assessment_answers   aa ON aq.id = aa.question_id
GROUP BY p.id, p.code, p.name;

-- View: unanswered_questions
DROP VIEW IF EXISTS `unanswered_questions`;
CREATE VIEW `unanswered_questions` AS
SELECT
    aq.id           AS question_id,
    aq.question,
    aq.practice_reference,
    p.code          AS process_code,
    p.name          AS process_name
FROM assessment_questions aq
JOIN  processes p ON aq.process_id = p.id
LEFT JOIN assessment_answers aa ON aq.id = aa.question_id
WHERE aa.id IS NULL;

-- View: design_factor_selected
-- Menampilkan hanya design factor yang dipilih (is_selected = 1)
DROP VIEW IF EXISTS `design_factor_selected`;
CREATE VIEW `design_factor_selected` AS
SELECT
    `code`,
    `name`,
    `description`
FROM `design_factors`
WHERE `is_selected` = 1
ORDER BY `code`;

-- View: gap_interpretation
-- Interpretasi GAP sesuai Tabel 4.7 pada skripsi
DROP VIEW IF EXISTS `gap_interpretation`;
CREATE VIEW `gap_interpretation` AS
SELECT
    p.code   AS process_code,
    p.name   AS process_name,
    r.capability_level  AS current_level,
    r.capability_percentage,
    r.rating,
    r.target_level,
    r.gap,
    CASE
        WHEN r.gap <= 0            THEN 'Tidak ada gap, sudah mencapai target'
        WHEN r.gap <= 1            THEN 'Gap kecil – perbaikan minor diperlukan'
        WHEN r.gap <= 2            THEN 'Gap sedang – perbaikan moderat diperlukan'
        WHEN r.gap <= 4            THEN 'Gap besar – perbaikan signifikan diperlukan'
        ELSE                            'Gap sangat besar – transformasi menyeluruh diperlukan'
    END      AS gap_status,
    r.as_is_description,
    r.to_be_description
FROM results r
JOIN processes p ON r.process_id = p.id
ORDER BY r.gap DESC;

-- ========================================================
-- Stored Procedures
-- ========================================================

DELIMITER //

-- Procedure: Hitung capability level untuk satu proses
-- Rumus: Total nilai / (jumlah pertanyaan x jumlah responden)
DROP PROCEDURE IF EXISTS `calculate_capability_level`//
CREATE PROCEDURE `calculate_capability_level`(IN p_process_id INT)
BEGIN
    SELECT
        p.code                                                          AS process_code,
        p.name                                                          AS process_name,
        COUNT(DISTINCT aq.id)                                           AS total_questions,
        SUM(aa.value)                                                   AS total_nilai,
        COUNT(DISTINCT aa.respondent_id)                                AS total_respondents,
        ROUND(SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3), 2)          AS capability_level,
        ROUND(SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3 * 5) * 100, 2) AS capability_percentage,
        4.00                                                            AS target_level,
        ROUND(4 - (SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3)), 2)   AS gap
    FROM processes p
    LEFT JOIN assessment_questions aq ON p.id = aq.process_id
    LEFT JOIN assessment_answers   aa ON aq.id = aa.question_id
    WHERE p.id = p_process_id
    GROUP BY p.id, p.code, p.name;
END//

-- Procedure: Laporan gap analysis untuk semua proses
-- Target Level = 4 sesuai Tabel 4.6 (To-Be Level 4 Predictable)
DROP PROCEDURE IF EXISTS `generate_gap_report`//
CREATE PROCEDURE `generate_gap_report`()
BEGIN
    SELECT
        p.code                                                              AS process_code,
        p.name                                                              AS process_name,
        ROUND(SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3), 2)             AS current_level,
        ROUND(SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3 * 5) * 100, 2)  AS percentage,
        4.00                                                                AS target_level,
        ROUND(4 - (SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3)), 2)      AS gap,
        CASE
            WHEN ROUND(4 - (SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3)), 2) <= 0 THEN 'Optimal'
            WHEN ROUND(4 - (SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3)), 2) <= 1 THEN 'Minor Gap'
            WHEN ROUND(4 - (SUM(aa.value) / (COUNT(DISTINCT aq.id) * 3)), 2) <= 2 THEN 'Moderate Gap'
            ELSE 'Major Gap'
        END                                                                 AS gap_status
    FROM processes p
    LEFT JOIN assessment_questions aq ON p.id = aq.process_id
    LEFT JOIN assessment_answers   aa ON aq.id = aa.question_id
    GROUP BY p.id, p.code, p.name
    ORDER BY gap DESC;
END//

-- Procedure: Rekap jawaban per responden per domain
-- Sesuai Tabel 4.10 (DSS01) dan Tabel 4.14 (DSS02) pada skripsi
DROP PROCEDURE IF EXISTS `respondent_summary`//
CREATE PROCEDURE `respondent_summary`(IN p_process_id INT)
BEGIN
    SELECT
        r.id        AS respondent_id,
        r.name      AS respondent_name,
        r.position,
        r.category,
        SUM(aa.value)   AS total_nilai
    FROM respondents r
    JOIN assessment_answers aa   ON r.id = aa.respondent_id
    JOIN assessment_questions aq ON aa.question_id = aq.id
    WHERE aq.process_id = p_process_id
    GROUP BY r.id, r.name, r.position, r.category
    ORDER BY r.id;
END//

DELIMITER ;

-- ========================================================
-- End of Database Schema
-- Kantor Desa Bogak Besar – COBIT 2019 Domain DSS
-- Sandy Donny Tampubolon (220141012) – STMIK Pelita Nusantara
-- ========================================================