# 📁 Penjelasan Proyek: Sistem Analisis Pengelolaan Layanan Desa Bogak Besar berbasis COBIT 2019

> **Path di repo ini menggunakan format** `folder/file.php#Lbaris` — bisa langsung diklik (link GitHub).

## 📌 APA ITU PROYEK INI?

Proyek ini adalah **website skripsi** untuk mengukur seberapa bagus **pengelolaan layanan TI (Teknologi Informasi)** di **Kantor Desa Bogak Besar**. Sistem ini menggunakan kerangka kerja **COBIT 2019** — yaitu standar internasional untuk tata kelola TI.

Bayangkan seperti ini: COBIT 2019 punya daftar **proses-proses TI** yang ideal (contoh: "proses menangani permintaan layanan", "proses mengelola operasional harian"). Aplikasi ini menilai sejauh mana desa sudah menjalankan proses-proses tersebut, lalu memberi **rekomendasi perbaikan** jika masih ada kekurangan.

### INFORMASI TEKNIS

| Aspek | Detail |
|---|---|
| **Bahasa** | PHP (tanpa framework) |
| **Arsitektur** | MVC (Model-View-Controller) buatan sendiri |
| **Database** | MySQL/MariaDB (via PDO) |
| **Frontend** | Bootstrap 5.3, Chart.js, CSS/JS kustom |
| **Database** | `cobit2019_bogakbesar` |
| **Organisasi** | Pemerintah Desa Bogak Besar, Kec. Tebing Tinggi, Kab. Serdang Bedagai |
| **Fokus Proses** | DSS01 (Manage Operations) & DSS02 (Manage Service Requests and Incidents) |

---

## 🗂️ ARSITEKTUR MVC

Proyek ini menggunakan pola **MVC buatan sendiri** (bukan Laravel/CodeIgniter).

| Komponen | Arti | Folder |
|---|---|---|
| **Model** | Seperti **arsip/data** — bertugas ambil/simpan data dari database | `app/models/` |
| **View** | Seperti **papan display** — yang dilihat user di browser | `app/views/` |
| **Controller** | Seperti **kasir/kondektur** — mengatur alur, menerima perintah, menyuruh Model ambil data, lalu menampilkan View | `app/controllers/` |

**Alur lengkap:**
```
Browser → index.php → core/App.php (router) → Controller → Model (database) → View → tampil ke user
```

---

## 📂 PENJELASAN FILE/FOLDER

---

### 1️⃣ `index.php` — GERBANG UTAMA (Front Controller)

**Path:** [`index.php`](index.php)

File **pertama** yang diakses oleh browser.

| Baris | Kode | Fungsi |
|---|---|---|
| [11](index.php#L11) | `session_start()` | Memulai sesi login |
| [21](index.php#L21) | `define('BASE_PATH', __DIR__)` | Menyimpan alamat folder proyek |
| [24](index.php#L24) | `require_once 'config/database.php'` | Memuat konfigurasi database |
| [27](index.php#L27) | `require_once 'core/App.php'` | Memuat router |
| [30-37](index.php#L30-L37) | `require_once 'app/controllers/...'` | Memuat semua controller (7 file) |
| [40](index.php#L40) | `App::route()` | Menjalankan router |

Ibaratnya: **pintu masuk mall** — semua pengunjung (browser) masuk lewat sini, baru diarahkan ke toko (halaman) yang dituju.

---

### 2️⃣ `config/database.php` — KONFIGURASI DATABASE

**Path:** [`config/database.php`](config/database.php)

| Baris | Kode | Fungsi |
|---|---|---|
| [8-12](config/database.php#L8-L12) | `define('DB_HOST', ...)` | Konfigurasi koneksi database (host, nama, user, password) |
| [18-51](config/database.php#L18-L51) | `class Database` | Class koneksi database — pola **Singleton** (hanya 1 koneksi) |
| [24](config/database.php#L24) | `new PDO($dsn, ...)` | Membuat koneksi ke database MySQL |
| [57-59](config/database.php#L57-L59) | `function getDB()` | Helper untuk ambil koneksi, dipanggil semua Model |

---

### 3️⃣ `core/App.php` — ROUTER & HELPERS (Jantung Aplikasi)

**Path:** [`core/App.php`](core/App.php)

**Router** adalah "peta jalan" aplikasi. Membaca parameter `?page=...` dari URL, lalu menentukan Controller & method mana yang dijalankan.

| Baris | Kode | Fungsi |
|---|---|---|
| [12-168](core/App.php#L12-L168) | `route()` | Routing utama — switch case berdasarkan parameter `$_GET['page']` |
| [16-46](core/App.php#L16-L46) | `$protectedPages` | Daftar halaman yang **wajib login**. Jika belum login → redirect ke login |
| [49-52](core/App.php#L49-L52) | `in_array(...)` | Cek autentikasi untuk halaman yang dilindungi |
| [62-168](core/App.php#L62-L168) | `switch ($page)` | Dispatch ke controller berdasarkan nilai `page` |
| [174-177](core/App.php#L174-L177) | `show404()` | Tampilkan halaman 404 jika halaman tidak dikenal |
| [182-185](core/App.php#L182-L185) | `redirect($page)` | Helper redirect ke halaman lain |
| [190-192](core/App.php#L190-L192) | `baseUrl()` | Helper untuk mendapatkan URL dasar |
| [197-199](core/App.php#L197-L199) | `sanitize($data)` | Membersihkan input dari karakter berbahaya |

#### Tabel Route Lengkap

| URL `?page=` | Controller::Method | Fungsi |
|---|---|---|
| `login` | AuthController::index() | Tampilkan form login |
| `auth-login` | AuthController::login() | Proses login |
| `logout` | AuthController::logout() | Keluar dari sesi |
| `dashboard` | DashboardController::index() | Halaman utama |
| `framework` | FrameworkController::index() | Domain COBIT |
| `proses` | FrameworkController::proses() | Detail proses DSS |
| `design-factor` | DesignFactorController::index() | Info design factor |
| `data-penilaian` | AssessmentController::index() | Form penilaian |
| `simpan-penilaian` | AssessmentController::save() | Simpan penilaian |
| `get-questions` | AssessmentController::getQuestions() | Ambil pertanyaan (AJAX) |
| `rekomendasi` | RecommendationController::index() | Hasil & rekomendasi |
| `rekomendasi-dss01` | RecommendationController::dss01() | Rekomendasi khusus DSS01 |
| `rekomendasi-dss02` | RecommendationController::dss02() | Rekomendasi khusus DSS02 |
| `respondents` | RespondentController::index() | Daftar responden |
| `save-respondent` | RespondentController::save() | Simpan responden |
| `delete-respondent` | RespondentController::delete() | Hapus responden |
| `processes` | ManagementController::processes() | Admin: kelola proses |
| `save-process` | ManagementController::saveProcess() | Admin: simpan proses |
| `delete-process` | ManagementController::deleteProcess() | Admin: hapus proses |
| `toggle-process` | ManagementController::toggleProcess() | Admin: aktif/nonaktif proses |
| `questions` | ManagementController::questions() | Admin: kelola pertanyaan |
| `save-question` | ManagementController::saveQuestion() | Admin: simpan pertanyaan |
| `delete-question` | ManagementController::deleteQuestion() | Admin: hapus pertanyaan |
| `toggle-question` | ManagementController::toggleQuestion() | Admin: aktif/nonaktif pertanyaan |
| `design-factors` | ManagementController::designFactors() | Admin: kelola design factor |
| `save-design-factor` | ManagementController::saveDesignFactor() | Admin: simpan design factor |
| `delete-design-factor` | ManagementController::deleteDesignFactor() | Admin: hapus design factor |
| `toggle-design-factor` | ManagementController::toggleDesignFactor() | Admin: aktif/nonaktif design factor |
| *(lainnya)* | App::show404() | Tampilkan halaman 404 |

---

### 4️⃣ `app/controllers/` — PARA KONTROLER (7 file)

Semua controller berbentuk **class static** (method dipanggil langsung tanpa membuat objek).

---

#### 4a. `AuthController.php` — Login/Logout

**Path:** [`app/controllers/AuthController.php`](app/controllers/AuthController.php)

| Method | Fungsi |
|---|---|
| `index()` | Menampilkan halaman login (`views/auth/login.php`) |
| `login()` | Menerima form (email & password), cocokkan via `User::findByEmail()`, verifikasi password, simpan session |
| `logout()` | Hancurkan session, redirect ke login |

**Hubungan:** Menggunakan **Model User** — query ke tabel `users`.

---

#### 4b. `DashboardController.php` — Halaman Utama

**Path:** [`app/controllers/DashboardController.php`](app/controllers/DashboardController.php)

| Baris | Kode | Fungsi |
|---|---|---|
| [19-23](app/controllers/DashboardController.php#L19-L23) | `new Process(), new Assessment(), ...` | Buat objek 5 model |
| [26-33](app/controllers/DashboardController.php#L26-L33) | `$stats = [...]` | Kumpulkan statistik: user, proses, design factor, penilaian, rata-rata, gap |
| [38-64](app/controllers/DashboardController.php#L38-L64) | `foreach ($processes as $process)` | Untuk setiap proses: hitung capability hari ini, generate rekomendasi |
| [47-48](app/controllers/DashboardController.php#L47-L48) | `calculateCapabilityLevel(...)` | Hitung capability level |
| [49](app/controllers/DashboardController.php#L49) | `generateRecommendation(...)` | Generate rekomendasi |
| [60-63](app/controllers/DashboardController.php#L60-L63) | Siapkan data Chart.js | Label, nilai current, target, gap |
| [66-72](app/controllers/DashboardController.php#L66-L72) | Update statistik | Berdasarkan data hari ini |
| [74](app/controllers/DashboardController.php#L74) | `require_once 'views/dashboard/index.php'` | Tampilkan view |

---

#### 4c. `FrameworkController.php` — Info Framework COBIT

**Path:** [`app/controllers/FrameworkController.php`](app/controllers/FrameworkController.php)

| Method | Fungsi |
|---|---|
| `index()` | Tampilkan domain COBIT (DSS, APO, MEA, EDM, BAI) dalam accordion |
| `proses()` | Tampilkan detail DSS01 & DSS02: alasan, fokus area |

**Catatan:** Data di controller ini **hardcode** (ditulis langsung di kode), bukan dari database.

---

#### 4d. `DesignFactorController.php` — Design Factor

**Path:** [`app/controllers/DesignFactorController.php`](app/controllers/DesignFactorController.php)

**Design Factor = Faktor Desain** — komponen COBIT 2019 yang menentukan bagaimana sistem tata kelola TI dirancang sesuai kondisi organisasi.

| Baris | Kode | Fungsi |
|---|---|---|
| [15-16](app/controllers/DesignFactorController.php#L15-L16) | `$designFactorModel->getAll()` | Ambil design factor dari database |
| [19-91](app/controllers/DesignFactorController.php#L19-L91) | `$dfDetails` | Penjelasan detail 4 design factor (hardcode): |

**Empat Design Factor yang Digunakan:**

**DF02 — Enterprise Goals** ([baris 20-37](app/controllers/DesignFactorController.php#L20-L37)): Tujuan strategis organisasi dan bagaimana TI mendukungnya.
- Fokus: Penyampaian layanan, keamanan informasi, kepatuhan, optimasi sumber daya, transformasi digital
- Konteks desa: Ketersediaan 24/7, keamanan data, efisiensi infrastruktur

**DF03 — Risk Profile** ([baris 38-55](app/controllers/DesignFactorController.php#L38-L55)): Profil risiko TI organisasi.
- Risiko: Ketersediaan sistem, kehilangan data, akses tidak sah, kegagalan infrastruktur, bencana
- Risiko desa: Server down, corrupt database, akses tidak berwenang

**DF04 — I&T Related Issues** ([baris 56-73](app/controllers/DesignFactorController.php#L56-L73)): Isu-isu terkait TI yang mempengaruhi tata kelola.
- Isu: Keterbatasan SDM TI, ketergantungan vendor, sistem lama
- Isu desa: Anggaran terbatas, ketergantungan developer, kurang tenaga TI profesional

**DF06 — Role of IT** ([baris 74-91](app/controllers/DesignFactorController.php#L74-L91)): Peran TI dalam organisasi.
- Peran: Supporter, driver, partner, transformer
- Konteks desa: Mendukung administrasi perkantoran, memfasilitasi pelayanan publik

---

#### 4e. `AssessmentController.php` — Jantung Penilaian

**Path:** [`app/controllers/AssessmentController.php`](app/controllers/AssessmentController.php)

Ini adalah **controller terpenting** — tempat user benar-benar melakukan penilaian.

**Method `index()`** ([baris 17-71](app/controllers/AssessmentController.php#L17-L71)) — Tampilkan form penilaian:

| Baris | Kode | Fungsi |
|---|---|---|
| [18-20](app/controllers/AssessmentController.php#L18-L20) | `new Process(), new Respondent(), new Assessment()` | Buat model |
| [22-23](app/controllers/AssessmentController.php#L22-L23) | `$processes = $processModel->getAll(true)` | Ambil semua proses aktif & responden |
| [26-27](app/controllers/AssessmentController.php#L26-L27) | `$selectedProcessId = isset($_GET['process']) ...` | Ambil proses yang dipilih (default: DSS01) |
| [30-31](app/controllers/AssessmentController.php#L30-L31) | `$selectedRespondentId = ...` | Ambil responden yang dipilih |
| [35-37](app/controllers/AssessmentController.php#L35-L37) | `$selectedDate = ...` | Ambil tanggal (default: hari ini) |
| [48](app/controllers/AssessmentController.php#L48) | `$assessmentModel->getQuestionsByProcess(...)` | Ambil pertanyaan sesuai proses |
| [50](app/controllers/AssessmentController.php#L50) | `$assessmentModel->getAnswerByQuestion(...)` | Ambil jawaban yang sudah ada |
| [58-65](app/controllers/AssessmentController.php#L58-L65) | `$ratingScale` | Skala penilaian 0-5 |
| [70](app/controllers/AssessmentController.php#L70) | `require_once 'views/assessment/index.php'` | Tampilkan form |

**Skala Penilaian COBIT** ([baris 58-65](app/controllers/AssessmentController.php#L58-L65)):

| Nilai | Label | Keterangan |
|---|---|---|
| 0 | Tidak Dilakukan | Praktik tidak dilaksanakan |
| 1 | Inisialisasi | Praktik baru mulai diterapkan |
| 2 | Terkelola | Praktik terdokumentasi dan dilaksanakan |
| 3 | Terdefinisi | Praktik standar dan terintegrasi |
| 4 | Terukur | Praktik terukur dan dikendalikan |
| 5 | Optimasi | Praktik terus diperbaiki |

**Method `save()`** ([baris 76-139](app/controllers/AssessmentController.php#L76-L139)) — Simpan penilaian:

| Baris | Kode | Fungsi |
|---|---|---|
| [82-85](app/controllers/AssessmentController.php#L82-L85) | `$processId, $answers, $date` | Ambil data dari form POST |
| [93-97](app/controllers/AssessmentController.php#L93-L97) | Validasi | Pastikan proses, responden, dan jawaban tidak kosong |
| [103-115](app/controllers/AssessmentController.php#L103-L115) | `foreach ($answers as $questionId => $value)` | Simpan setiap jawaban ke tabel `assessment_answers` |
| [118](app/controllers/AssessmentController.php#L118) | `calculateCapabilityLevel(...)` | Hitung **Capability Level** (rata-rata semua nilai) |
| [125](app/controllers/AssessmentController.php#L125) | `generateRecommendation(...)` | Generate rekomendasi berdasarkan gap |
| [128-133](app/controllers/AssessmentController.php#L128-L133) | `saveResult(...)` | Simpan hasil ke tabel `results` |

**Method `getQuestions()`** ([baris 144-177](app/controllers/AssessmentController.php#L144-L177)) — Endpoint AJAX:
- Menerima `process_id` dan `respondent_id` via GET
- Mengembalikan data pertanyaan + nilai yang sudah ada dalam format JSON
- Digunakan untuk memuat pertanyaan secara dinamis tanpa reload halaman

---

#### 4f. `RecommendationController.php` — Hasil & Rekomendasi

**Path:** [`app/controllers/RecommendationController.php`](app/controllers/RecommendationController.php)

| Baris | Kode | Fungsi |
|---|---|---|
| [25-28](app/controllers/RecommendationController.php#L25-L28) | `$selectedDate = ...` | Ambil filter tanggal dari URL |
| [32-34](app/controllers/RecommendationController.php#L32-L34) | `$selectedProcessId = ...` | Ambil filter proses (0 = semua) |
| [42-51](app/controllers/RecommendationController.php#L42-L51) | `if ($selectedProcessId > 0) ...` | Ambil proses spesifik atau semua proses |
| [56-89](app/controllers/RecommendationController.php#L56-L89) | `foreach ($processes as $process)` | Untuk setiap proses: hitung capability, generate rekomendasi |
| [59-64](app/controllers/RecommendationController.php#L59-L64) | `calculateCapabilityLevel(...)` | Hitung capability level |
| [67-72](app/controllers/RecommendationController.php#L67-L72) | `generateRecommendation(...)` | Generate rekomendasi berdasarkan gap |
| [92-105](app/controllers/RecommendationController.php#L92-L105) | Hitung statistik | Total proses, rata-rata capability, total gap |
| [108-113](app/controllers/RecommendationController.php#L108-L113) | Urutkan berdasarkan gap | Tentukan **proses prioritas** (gap terbesar) |
| [132-141](app/controllers/RecommendationController.php#L132-L141) | `SELECT DISTINCT respondent_id ...` | Hitung jumlah responden berdasarkan tanggal |
| [161](app/controllers/RecommendationController.php#L161) | `require_once 'views/recommendation/index.php'` | Tampilkan view |

**Method `dss01()`** ([baris 163-167](app/controllers/RecommendationController.php#L163-L167)) — Set proses = 1 (DSS01), panggil `index()`.
**Method `dss02()`** ([baris 169-173](app/controllers/RecommendationController.php#L169-L173)) — Set proses = 2 (DSS02), panggil `index()`.

---

#### 4g. `RespondentController.php` — Kelola Responden

**Path:** [`app/controllers/RespondentController.php`](app/controllers/RespondentController.php)

**Responden** adalah orang-orang yang dinilai/diwawancarai. Biasanya aparatur desa:
- **Operator Sistem** — mengoperasikan sistem TI
- **Perangkat Desa** — staf kantor desa

Method: `index()` (tampilkan form & tabel), `save()` (simpan data), `delete()` (hapus data).

---

#### 4h. `ManagementController.php` — Admin (Master Data)

**Path:** [`app/controllers/ManagementController.php`](app/controllers/ManagementController.php)

Khusus user dengan role **admin**. Dilindungi `requireAdmin()`.

| Method | Fungsi |
|---|---|
| `processes()` / `saveProcess()` / `deleteProcess()` / `toggleProcess()` | CRUD proses COBIT |
| `questions()` / `saveQuestion()` / `deleteQuestion()` / `toggleQuestion()` | CRUD pertanyaan assessment |
| `designFactors()` / `saveDesignFactor()` / `deleteDesignFactor()` / `toggleDesignFactor()` | CRUD design factor |

---

### 5️⃣ `app/models/` — PARA MODEL (6 file)

Model bertugas **berinteraksi dengan database**. Semua menggunakan PDO dengan prepared statement (aman dari SQL injection).

---

#### 5a. `Assessment.php` — Model Penilaian

**Path:** [`app/models/Assessment.php`](app/models/Assessment.php)

| Method | Baris | Fungsi |
|---|---|---|
| `getQuestionsByProcess($processId)` | [19-29](app/models/Assessment.php#L19-L29) | Ambil pertanyaan untuk suatu proses |
| `getAllQuestions($activeOnly)` | [31-40](app/models/Assessment.php#L31-L40) | Ambil semua pertanyaan + join dengan proses |
| `getQuestionById($id)` | [42-46](app/models/Assessment.php#L42-L46) | Ambil 1 pertanyaan berdasarkan ID |
| `createQuestion($data)` | [48-57](app/models/Assessment.php#L48-L57) | Buat pertanyaan baru |
| `updateQuestion($data)` | [59-69](app/models/Assessment.php#L59-L69) | Update pertanyaan |
| `deleteQuestion($id)` | [71-74](app/models/Assessment.php#L71-L74) | Hapus pertanyaan |
| `toggleQuestionActive($id, $active)` | [76-79](app/models/Assessment.php#L76-L79) | Aktif/nonaktif pertanyaan |
| `saveAnswer($data)` | [107-155](app/models/Assessment.php#L107-L155) | **Simpan jawaban**: cek existing → update atau insert baru. Per tanggal. |
| `getAnswerByQuestion($questionId, $respondentId, $date)` | [184-202](app/models/Assessment.php#L184-L202) | Ambil jawaban spesifik berdasarkan pertanyaan, responden, tanggal |
| `calculateCapabilityLevel($processId, $respondentId, $date)` | [209-251](app/models/Assessment.php#L209-L251) | **RUMUS UTAMA** — hitung capability level (rata-rata nilai) |
| `getAssessmentDatesByRespondent($respondentId)` | [261-273](app/models/Assessment.php#L261-L273) | Ambil tanggal penilaian untuk responden |
| `getAssessmentDates()` | [278-281](app/models/Assessment.php#L278-L281) | Ambil semua tanggal penilaian |
| `countAssessments()` | [286-289](app/models/Assessment.php#L286-L289) | Hitung total penilaian |
| `countAssessmentsByDate($date)` | [294-298](app/models/Assessment.php#L294-L298) | Hitung penilaian per tanggal |
| `resetAnswers()` | [303-305](app/models/Assessment.php#L303-L305) | Hapus semua jawaban |

**Rumus Capability Level** ([baris 209-251](app/models/Assessment.php#L209-L251)):

```sql
SELECT SUM(a.value) as total_score, COUNT(a.id) as total_answers
FROM assessment_answers a
JOIN assessment_questions q ON a.question_id = q.id
WHERE q.process_id = :process_id
```

Kode PHP ([baris 243](app/models/Assessment.php#L243)): `$capability = $result['total_score'] / $result['total_answers'];`

Contoh: 10 pertanyaan DSS01, total nilai = 28 → Capability = 28/10 = **2.80**

---

#### 5b. `Result.php` — Model Hasil & Rekomendasi

**Path:** [`app/models/Result.php`](app/models/Result.php)

| Method | Baris | Fungsi |
|---|---|---|
| `getAll()` | [19-27](app/models/Result.php#L19-L27) | Ambil semua hasil + join processes |
| `getByProcess($processId)` | [32-42](app/models/Result.php#L32-L42) | Ambil hasil per proses |
| `saveResult($data)` | [47-73](app/models/Result.php#L47-L73) | Simpan/update hasil (upsert) |
| `getAverageCapabilityLevel()` | [78-81](app/models/Result.php#L78-L81) | Rata-rata semua capability level |
| `countGaps()` | [86-89](app/models/Result.php#L86-L89) | Hitung proses yang punya gap > 0 |
| `generateRecommendation($capabilityLevel, $targetLevel, $processCode)` | [94-214](app/models/Result.php#L94-L214) | **Generate teks rekomendasi** |

**Method PENTING — `generateRecommendation()`** ([baris 94-214](app/models/Result.php#L94-L214)):

| Baris | Kode | Fungsi |
|---|---|---|
| [95](app/models/Result.php#L95) | `$gap = $targetLevel - $capabilityLevel` | Hitung gap (target 4.0 - nilai sekarang) |
| [98-108](app/models/Result.php#L98-L108) | `if ($gap <= 0)` | Jika sudah mencapai target → "Optimal" |
| [111](app/models/Result.php#L111) | `$gapLevel = (int) ceil($gap)` | Tentukan tingkat keparahan gap (1-5) |
| [114-155](app/models/Result.php#L114-L155) | `$dss01 = [...]` | **Array 5 level rekomendasi untuk DSS01** |
| [158-199](app/models/Result.php#L158-L199) | `$dss02 = [...]` | **Array 5 level rekomendasi untuk DSS02** |
| [201](app/models/Result.php#L201) | `$pool = ...` | Pilih array sesuai kode proses |
| [203-205](app/models/Result.php#L203-L205) | `if (isset($pool[$gapLevel]))` | Kembalikan teks sesuai gap level |

**Isi Rekomendasi DSS01 (Manage Operations):**

| Gap Level | Inti Rekomendasi | Baris |
|---|---|---|
| 1 (Minor) | Buat SOP tertulis — panduan menyalakan/mematikan komputer, merawat printer, cek jaringan | [120](app/models/Result.php#L120) |
| 2 (Moderate) | Sediakan Buku Ceklis Harian — periksa kondisi komputer, tinta, kabel, UPS setiap pagi | [128](app/models/Result.php#L128) |
| 3 (Major) | Kepala Desa harus awasi & evaluasi rutinitas penggunaan fasilitas kerja | [136](app/models/Result.php#L136) |
| 4 (Major) | Implementasi aplikasi/modul khusus untuk kelola jadwal pemeliharaan perangkat | [144](app/models/Result.php#L144) |
| 5 (Major) | Alokasi anggaran APBDes khusus TI + SK Kepala Desa untuk penanggung jawab fasilitas | [152](app/models/Result.php#L152) |

**Isi Rekomendasi DSS02 (Manage Service Requests and Incidents):**

| Gap Level | Inti Rekomendasi | Baris |
|---|---|---|
| 1 (Minor) | Buat SOP Penanganan Insiden — alur pelaporan saat komputer lambat/print macet/internet putus | [164](app/models/Result.php#L164) |
| 2 (Moderate) | Sediakan Buku Riwayat Insiden — catat waktu, penyebab, dan perbaikan setiap kerusakan | [172](app/models/Result.php#L172) |
| 3 (Major) | Sekdes tetapkan target waktu pemulihan — internet maksimal 1 jam, printer maksimal 30 menit | [180](app/models/Result.php#L180) |
| 4 (Major) | Integrasi fitur pelaporan insiden digital ke aplikasi desa + verifikasi kelayakan setelah perbaikan | [188](app/models/Result.php#L188) |
| 5 (Major) | Buat dokumen rencana darurat + anggaran tak terduga + MoU dengan teknisi eksternal | [196](app/models/Result.php#L196) |

---

#### 5c. `Process.php` — Model Proses COBIT

**Path:** [`app/models/Process.php`](app/models/Process.php)

CRUD untuk tabel `processes`. Method: `getAll()`, `getById()`, `getByCode()`, `create()`, `update()`, `delete()`, `toggleActive()`, `countAll()`.

---

#### 5d. `DesignFactor.php` — Model Design Factor

**Path:** [`app/models/DesignFactor.php`](app/models/DesignFactor.php)

CRUD untuk tabel `design_factors`. Method sama seperti Process, plus **natural sorting** ([baris 29-37](app/models/DesignFactor.php#L29-L37)): mengurutkan DF1, DF2, DF10 secara numerik (bukan string biasa yang jadi DF1, DF10, DF2).

---

#### 5e. `Respondent.php` — Model Responden

**Path:** [`app/models/Respondent.php`](app/models/Respondent.php)

CRUD sederhana untuk tabel `respondents`. Method: `getAll()`, `getById()`, `create()`, `update()`, `delete()`.

---

#### 5f. `User.php` — Model User

**Path:** [`app/models/User.php`](app/models/User.php)

Untuk tabel `users`. Method: `findByEmail()`, `findById()`, `create()` (bcrypt), `verifyPassword()`, `updatePassword()`, `countAll()`.

---

### 6️⃣ `app/views/` — TAMPILAN (13 file)

Semua view menggunakan **output buffering**: konten ditangkap dengan `ob_start()`, lalu disisipkan ke layout utama.

---

#### 6a. `layouts/main.php` — LAYOUT UTAMA (Template)

**Path:** [`app/views/layouts/main.php`](app/views/layouts/main.php)

Ini adalah **kerangka** semua halaman (kecuali login).

| Baris | Kode | Fungsi |
|---|---|---|
| [9-11](app/views/layouts/main.php#L9-L11) | `session_start()` | Pastikan session berjalan |
| [32-34](app/views/layouts/main.php#L32-L34) | CDN Bootstrap 5 CSS & Icons | Framework CSS |
| [36](app/views/layouts/main.php#L36) | `public/assets/css/style.css` | CSS kustom |
| [39](app/views/layouts/main.php#L39) | CDN Chart.js | Library grafik |
| [49-201](app/views/layouts/main.php#L49-L201) | Sidebar | Menu navigasi (lihat detail di bawah) |
| [206-228](app/views/layouts/main.php#L206-L228) | Top Navbar | Nama user, role, tombol toggle sidebar |
| [232](app/views/layouts/main.php#L232) | `<?php echo $content; ?>` | Tempat konten view dimasukkan |
| [250-252](app/views/layouts/main.php#L250-L252) | Bootstrap JS + `main.js` | JavaScript |

**Struktur Sidebar** ([baris 58-194](app/views/layouts/main.php#L58-L194)):

| Menu | Baris | Submenu |
|---|---|---|
| Dashboard | [60-65](app/views/layouts/main.php#L60-L65) | — |
| Framework COBIT | [68-102](app/views/layouts/main.php#L68-L102) | Domain COBIT 2019, Proses DSS |
| Design Factor | [103-108](app/views/layouts/main.php#L103-L108) | — |
| Analisis (Penilaian) | [111-116](app/views/layouts/main.php#L111-L116) | — |
| Responden | [117-122](app/views/layouts/main.php#L117-L122) | — |
| Master Data *(khusus admin)* | [123-158](app/views/layouts/main.php#L123-L158) | Domain/Proses, Pertanyaan, Design Factor |
| Rekomendasi | [159-193](app/views/layouts/main.php#L159-L193) | Rekomendasi DSS01, Rekomendasi DSS02 |

---

#### 6b. `dashboard/index.php` — Halaman Dashboard

**Path:** [`app/views/dashboard/index.php`](app/views/dashboard/index.php)

| Baris | Konten |
|---|---|
| [14-28](app/views/dashboard/index.php#L14-L28) | Welcome card — "Selamat Datang, [Nama]!" |
| [30-52](app/views/dashboard/index.php#L30-L52) | Info peran admin (hanya untuk admin) |
| [55-80](app/views/dashboard/index.php#L55-L80) | 2 grafik Chart.js: Capability Level DSS01 & DSS02 |
| [83-153](app/views/dashboard/index.php#L83-L153) | Tabel hasil penilaian — progress bar, gap, status badge |
| [155-233](app/views/dashboard/index.php#L155-L233) | Script Chart.js — data dari PHP `$chartCurrent`, `$chartTarget` |

**Cara view terhubung ke layout** ([baris 235-236](app/views/dashboard/index.php#L235-L236)):
```php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
```

---

#### 6c. `auth/login.php` — Halaman Login

**Path:** [`app/views/auth/login.php`](app/views/auth/login.php)

Halaman **standalone** (tidak pakai layout main.php). Background gradien ungu. Form email + password + toggle lihat password + hint demo credentials.

---

#### 6d. `framework/index.php` — Domain COBIT

**Path:** [`app/views/framework/index.php`](app/views/framework/index.php)

Accordion Bootstrap menampilkan 5 domain COBIT: **DSS** (Delivery Service & Support), **APO** (Align, Plan & Organise), **MEA** (Monitor, Evaluate & Assess), **EDM** (Evaluate, Direct & Monitor), **BAI** (Build, Acquire & Implement). Masing-masing dengan daftar proses dan tautan penilaian.

---

#### 6e. `framework/proses.php` — Detail Proses DSS

**Path:** [`app/views/framework/proses.php`](app/views/framework/proses.php)

Tabel explain: DSS01 dan DSS02 dengan alasan dipilih, fokus area, dan tautan kuesioner.

---

#### 6f. `design-factor/index.php` — Design Factor

**Path:** [`app/views/design-factor/index.php`](app/views/design-factor/index.php)

Menampilkan design factor dari database (tabel) + penjelasan detail DF02, DF03, DF04, DF06 (dari controller).

---

#### 6g. `assessment/index.php` — Form Penilaian

**Path:** [`app/views/assessment/index.php`](app/views/assessment/index.php)

Form utama: pilih responden, proses, tanggal → tampil pertanyaan dengan radio button 0-5 → ada progress tracking.

---

#### 6h. `recommendation/index.php` — Hasil & Rekomendasi (999 baris)

**Path:** [`app/views/recommendation/index.php`](app/views/recommendation/index.php)

Halaman paling kompleks. Berisi:

| Konten | Fungsi |
|---|---|
| Filter tanggal | Pilih tanggal penilaian |
| Summary cards | Capability level, total gap, target |
| Grafik Chart.js | Bar chart perbandingan current vs target |
| Alert prioritas | Proses dengan gap terbesar |
| Tabel hasil | Per proses: level, gap, status |
| Kartu rekomendasi | Detail rekomendasi per proses |
| Tombol Cetak Laporan | Buka window baru dengan format surat resmi desa (logo, kop, tanda tangan Kades) |

---

#### 6i. `respondent/index.php` — Kelola Responden

**Path:** [`app/views/respondent/index.php`](app/views/respondent/index.php)

Form tambah/edit responden + tabel daftar responden dengan tombol edit & hapus.

---

#### 6j-6l. `admin/` — Halaman Admin (3 file)

| File | Path | Fungsi |
|---|---|---|
| `admin/processes.php` | [`app/views/admin/processes.php`](app/views/admin/processes.php) | Form + tabel kelola proses COBIT |
| `admin/questions.php` | [`app/views/admin/questions.php`](app/views/admin/questions.php) | Form + tabel kelola pertanyaan assessment |
| `admin/design_factors.php` | [`app/views/admin/design_factors.php`](app/views/admin/design_factors.php) | Form + tabel kelola design factor |

Masing-masing berisi: form input, tabel data, tombol edit/hapus/toggle aktif.

---

#### 6m. `errors/404.php` — Halaman Error

**Path:** [`app/views/errors/404.php`](app/views/errors/404.php)

Halaman 404 kustom dengan gradien ungu dan tautan kembali ke dashboard.

---

### 7️⃣ `public/assets/` — FILE STATIS

#### 7a. `css/style.css` — CSS Utama

**Path:** [`public/assets/css/style.css`](public/assets/css/style.css) (691 baris)

CSS kustom: variabel CSS, sidebar collapsible, card styles, progress bar, animasi, responsive breakpoints.

#### 7b. `css/login.css` — CSS Login

**Path:** [`public/assets/css/login.css`](public/assets/css/login.css)

Background gradien ungu, form login terpusat.

#### 7c. `js/main.js` — JavaScript

**Path:** [`public/assets/js/main.js`](public/assets/js/main.js) (264 baris)

Fungsi: toggle sidebar (mobile: slide-in + overlay, desktop: collapse/expand), click-outside-to-close, submenu management.

#### 7d. `img/Lambang_Kabupaten_Serdang_Bedagai.png`

**Path:** [`public/assets/img/Lambang_Kabupaten_Serdang_Bedagai.png`](public/assets/img/Lambang_Kabupaten_Serdang_Bedagai.png)

Logo Kabupaten Serdang Bedagai — digunakan di laporan cetak rekomendasi.

---

### 8️⃣ `database.sql` — STRUKTUR DATABASE LENGKAP

**Path:** [`database.sql`](database.sql)

Berisi seluruh struktur database + data contoh (seed).

#### 6 Tabel:

**`users`** — User login
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| name | varchar(100) | Nama user |
| email | varchar(100) UNIQUE | Email login |
| password | varchar(255) | Bcrypt hash |
| role | enum('admin','user') | Role pengguna |

**`processes`** — Proses COBIT
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| code | varchar(10) UNIQUE | Kode proses (DSS01, DSS02) |
| name | varchar(100) | Nama proses |
| description | text | Deskripsi |
| is_active | tinyint(1) | Status aktif |

**`design_factors`** — Design Factor
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| code | varchar(10) UNIQUE | Kode (DF02, DF03, dll) |
| name | varchar(100) | Nama design factor |
| description | text | Deskripsi |
| is_active | tinyint(1) | Status aktif |

**`assessment_questions`** — Pertanyaan Penilaian
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| process_id | int(11) | FK → processes.id (CASCADE) |
| question | text | Teks pertanyaan |
| practice_reference | varchar(20) | Referensi praktik (DSS01.01) |
| weight | tinyint(3) | Bobot (default 1) |
| is_active | tinyint(1) | Status aktif |

**`assessment_answers`** — Jawaban Penilaian
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| question_id | int(11) | FK → assessment_questions.id (CASCADE) |
| respondent_id | int(11) | FK → respondents.id (CASCADE) |
| assessment_date | date | Tanggal penilaian |
| value | tinyint(3) | Nilai 0-5 |
| notes | text | Catatan (nullable) |
| answered_by | int(11) | FK → users.id (SET NULL) |

UNIQUE KEY: (question_id, respondent_id, assessment_date)

**`respondents`** — Responden
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| name | varchar(100) | Nama responden |
| position | varchar(100) | Posisi/jabatan |
| category | enum('operator_sistem','perangkat_desa') | Kategori |

**`results`** — Hasil Analisis
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | int(11) AUTO_INCREMENT | Primary Key |
| process_id | int(11) UNIQUE | FK → processes.id (CASCADE) |
| capability_level | decimal(3,2) | Nilai capability (0.00-5.00) |
| target_level | decimal(3,2) | Target (default 4.00) |
| gap | decimal(3,2) | Selisih target - capability |
| recommendation | longtext (JSON) | Data rekomendasi (JSON) |

#### 2 View:

| View | Fungsi |
|---|---|
| `assessment_summary` | Rangkuman nilai capability per proses |
| `unanswered_questions` | Pertanyaan yang belum dijawab |

---

### 9️⃣ FILE LAINNYA

| File | Fungsi |
|---|---|
| [`fix_design_factors.sql`](fix_design_factors.sql) | Perbaikan AUTO_INCREMENT tabel design_factors |
| [`scripts/dump_today.php`](scripts/dump_today.php) | Script CLI — ekspor hasil hari ini ke JSON (untuk cron/otomatisasi) |
| [`.fk_check.php`](.fk_check.php) | Debug — cek integritas foreign key assessment_answers → respondents |
| [`.schema_check.php`](.schema_check.php) | Debug — lihat kolom tabel assessment_answers |
| [`.schema_index.php`](.schema_index.php) | Debug — lihat index tabel assessment_answers |

---

## 🔄 ALUR LENGKAP SISTEM

```
                              ┌─────────────────────────────────────────────────────────────────────────────┐
                              │                         DATABASE (MySQL)                                   │
                              │  ┌──────────┐  ┌─────────────┐  ┌──────────┐  ┌─────────────┐  ┌────────┐ │
                              │  │processes │  │questions    │  │ answers  │  │respondents  │  │results │ │
                              │  │DSS01,02  │  │20 soal      │  │nilai 0-5 │  │nama,posisi  │  │level   │ │
                              │  └─────┬────┘  └──────┬──────┘  └─────┬────┘  └──────┬──────┘  └────┬───┘ │
                              │        │               │              │               │               │      │
                              │  ┌─────┴───────────────┴──────────────┴───────────────┴───────────────┴───┐ │
                              │  │                       MODEL (app/models/)                              │ │
                              │  │  Process.php  Assessment.php  Respondent.php  Result.php              │ │
                              │  │  DesignFactor.php  User.php                                           │ │
                              │  └────────────────────────────────┬───────────────────────────────────────┘ │
                              └───────────────────────────────────┼─────────────────────────────────────────┘
                                                                  │
┌─────────────────────────────────────────────────────────────────┼─────────────────────────────────────────┐
│                          CONTROLLER (app/controllers/)                                                   │
│                                                                                                          │
│   ┌─────────────────────────────────────────────────────────────┐                                       │
│   │                 index.php (Front Controller)                │                                       │
│   │  session_start() → load config/core/controllers → route()  │                                       │
│   └──────────────────────────┬──────────────────────────────────┘                                       │
│                              │                                                                          │
│               ┌──────────────┴──────────────┐                                                           │
│               │        App::route()         │                                                           │
│               │   (core/App.php baris 12)   │                                                           │
│               └──────────────┬──────────────┘                                                           │
│                              │                                                                          │
│          ┌───────────────────┼───────────────────┐                                                      │
│          ▼                   ▼                   ▼                                                       │
│  ┌───────────────┐  ┌───────────────┐  ┌───────────────┐                                                │
│  │AuthController │  │DashboardCont. │  │FrameworkCont. │  ...dan 4 controller lainnya                    │
│  │login/logout   │  │statistik+chart│  │info COBIT     │                                                │
│  └───────┬───────┘  └───────┬───────┘  └───────┬───────┘                                                │
│          │                  │                   │                                                         │
│  ┌───────┴───────┐  ┌───────┴───────┐  ┌───────┴───────┐                                                │
│  │User model     │  │5 model       │  │Process model │                                                │
│  └───────────────┘  └───────────────┘  └───────────────┘                                                │
│                                                                                                          │
│   ┌─────────────────────────────────────────────────────────────┐                                       │
│   │              AssessmentController (PENILAIAN)               │                                       │
│   │  index():  form → pilih responden + proses + tanggal       │                                       │
│   │  save():   simpan jawaban → hitung capability → rekomendasi│                                       │
│   │  getQuestions():  AJAX → kirim JSON pertanyaan             │                                       │
│   └─────────────────────────────────────────────────────────────┘                                       │
│                                                                                                          │
│   ┌─────────────────────────────────────────────────────────────┐                                       │
│   │           RecommendationController (REKOMENDASI)            │                                       │
│   │  index():  filter tanggal → hitung semua proses →          │                                       │
│   │            statistik → prioritas → tampilkan view          │                                       │
│   └─────────────────────────────────────────────────────────────┘                                       │
│                                                                                                          │
│   ┌─────────────────────────────────────────────────────────────┐                                       │
│   │      ManagementController (ADMIN) — dilindungi admin        │                                       │
│   │  processes()  questions()  designFactors()                  │                                       │
│   │  + save/delete/toggle untuk masing-masing                   │                                       │
│   └─────────────────────────────────────────────────────────────┘                                       │
└─────────────────────────────────────────────────┬─────────────────────────────────────────────────────────┘
                                                  │
┌─────────────────────────────────────────────────┼─────────────────────────────────────────────────────────┐
│                      VIEW (app/views/)                                                                     │
│                                                                                                          │
│  ┌────────────────────────────────────────────────────────────────────────────────────────────────────┐  │
│  │                         layouts/main.php — KERANGKA UTAMA                                        │  │
│  │  Bootstrap 5 + Chart.js + CSS custom + Sidebar + Navbar + Footer                                  │  │
│  │  Tempat konten: <?php echo $content; ?> ([baris 232](app/views/layouts/main.php#L232))                                               │  │
│  └────────────────────────────────────────────────────────────────────────────────────────────────────┘  │
│                           ▲                                                                               │
│                           │                                                                               │
│          ┌────────────────┼────────────────┬───────────────┬───────────────┬──────────────┐                │
│          ▼                ▼                ▼               ▼               ▼              ▼                │
│  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌────────────┐         │
│  │dashboard   │  │assessment  │  │design-factor│  │recommend.  │  │respondent  │  │admin/*.php  │         │
│  │index.php   │  │index.php   │  │index.php    │  │index.php   │  │index.php   │  │(3 file)    │         │
│  │grafik+table│  │form 0-5    │  │info DF      │  │hasil+laporan│  │form+table  │  │CRUD data   │         │
│  └────────────┘  └────────────┘  └────────────┘  └────────────┘  └────────────┘  └────────────┘         │
└───────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🎯 DESIGN FACTOR: LOKASI DAN HUBUNGAN

**Design Factor** dalam COBIT 2019 adalah faktor-faktor yang **mempengaruhi bagaimana sistem tata kelola TI harus dirancang**. Dalam proyek ini, design factor muncul di beberapa tempat:

### 1. Database — `design_factors`

Tabel `design_factors` di database `cobit2019_bogakbesar`. Disimpan sebagai data master yang bisa ditambah/diedit lewat halaman admin.

### 2. Model — `app/models/DesignFactor.php`

Class `DesignFactor` ([baris 9-98](app/models/DesignFactor.php#L9-L98)). CRUD lengkap + natural sorting ([baris 29-37](app/models/DesignFactor.php#L29-L37)). Method dipanggil oleh:
- `DesignFactorController` — untuk menampilkan ke user
- `ManagementController` — untuk admin mengelola data
- `DashboardController` — untuk statistik (countAll)

### 3. Controller — `DesignFactorController.php`

**Path:** [`app/controllers/DesignFactorController.php`](app/controllers/DesignFactorController.php)

Method `index()` ([baris 14-94](app/controllers/DesignFactorController.php#L14-L94)):
- **[Baris 15-16](app/controllers/DesignFactorController.php#L15-L16):** Ambil data dari database via model
- **[Baris 19-91](app/controllers/DesignFactorController.php#L19-L91):** Array `$dfDetails` berisi penjelasan 4 design factor dalam konteks Desa Bogak Besar:
  - **DF02 (Enterprise Goals)** [baris 20-37](app/controllers/DesignFactorController.php#L20-L37): Tujuan desa — layanan TI 24/7, keamanan data
  - **DF03 (Risk Profile)** [baris 38-55](app/controllers/DesignFactorController.php#L38-L55): Risiko — server down, data corrupt, akses tidak sah
  - **DF04 (I&T Related Issues)** [baris 56-73](app/controllers/DesignFactorController.php#L56-L73): Masalah — anggaran terbatas, ketergantungan developer
  - **DF06 (Role of IT)** [baris 74-91](app/controllers/DesignFactorController.php#L74-L91): Peran TI — supporter administrasi & driver pembelajaran digital

### 4. View (User) — `app/views/design-factor/index.php`

Menampilkan design factor dari database dalam bentuk tabel + penjelasan detail 4 factor.

### 5. View (Admin) — `app/views/admin/design_factors.php`

Form CRUD untuk mengelola data design factor — khusus admin.

### 6. Controller (Admin) — `ManagementController`

Method `designFactors()`, `saveDesignFactor()`, `deleteDesignFactor()`, `toggleDesignFactor()` — CRUD.

### Hubungan Design Factor dengan Seluruh Sistem:

Design Factor **tidak secara langsung** mempengaruhi perhitungan capability level. Fungsinya sebagai **kerangka acuan/konteks**:

```
DF02 (Enterprise Goals)
  → Tujuan desa: pelayanan administrasi handal
  → Maka proses DSS01 (Manage Operations) & DSS02 (Manage Service Requests) dipilih
  → Pertanyaan assessment dirancang untuk mengukur proses tersebut

DF03 (Risk Profile)
  → Risiko: server down, data hilang
  → Maka pertanyaan difokuskan pada: pemeliharaan server, backup data, penanganan insiden

DF04 (I&T Related Issues)
  → Masalah: anggaran terbatas, kurang tenaga TI
  → Maka rekomendasi disesuaikan: SOP sederhana, buku ceklis, SK Kades (bukan beli software mahal)

DF06 (Role of IT)
  → Peran TI: supporter (bukan transformer)
  → Maka target level 4.0 (Terukur) sudah cukup realistis, bukan 5.0 (Optimasi)
```

**Contoh nyata dalam kode:**
- Pilihan proses DSS01 & DSS02 didasarkan pada DF02 (Enterprise Goals desa)
- Pertanyaan assessment (di database tabel `assessment_questions`) mencakup: pemeliharaan perangkat, penanganan insiden, backup data — sesuai DF03 (Risk Profile)
- Rekomendasi di [`Result.php` baris 114-199](app/models/Result.php#L114-L199) menggunakan bahasa dan solusi yang sesuai konteks desa (SOP, buku ceklis, SK Kades, APBDes) — bukan solusi enterprise mahal

---

## 🧮 RUMUS PERHITUNGAN CAPABILITY LEVEL

**Lokasi:** [`app/models/Assessment.php` baris 209-251](app/models/Assessment.php#L209-L251)

```php
public function calculateCapabilityLevel($processId, $respondentId = null, $date = null)
{
    // SQL: jumlah total nilai / jumlah total jawaban
    $sql = "SELECT SUM(a.value) as total_score, COUNT(a.id) as total_answers
            FROM assessment_answers a
            JOIN assessment_questions q ON a.question_id = q.id
            WHERE q.process_id = :process_id
              AND a.respondent_id = :respondent_id
              AND a.assessment_date = :date";

    $result = $stmt->fetch();
    $capability = $result['total_score'] / $result['total_answers'];
    return round($capability, 2);
}
```

**Contoh perhitungan:**
| Pertanyaan | Nilai (0-5) |
|---|---|
| Apakah tersedia SOP pengoperasian komputer? | 2 |
| Apakah dilakukan pengecekan harian printer? | 1 |
| Apakah ada jadwal pemeliharaan server? | 0 |
| Apakah koneksi internet dipantau setiap pagi? | 3 |
| Apakah ada buku inventaris perangkat? | 1 |
| **Total** | **7** |
| **Capability Level** | **7 ÷ 5 = 1.40** |
| **Target** | **4.00** |
| **Gap** | **4.00 - 1.40 = 2.60** |

Gap 2.60 → ceil(2.60) = **gap level 3** → rekomendasi "Major Gap": Kepala Desa harus mulai berperan aktif mengawasi dan mengevaluasi.

---

## 👥 STRUKTUR PENGGUNA

Ada 2 role:

| Role | Hak Akses |
|---|---|
| **Admin** | Semua akses: dashboard, penilaian, responden, master data (proses, pertanyaan, design factor), rekomendasi |
| **User** | Dashboard, penilaian, responden, rekomendasi — **tidak bisa** akses Master Data |

Pengecekan role ada di:
- **Layout:** [`main.php` baris 123](app/views/layouts/main.php#L123) — `if ($_SESSION['user_role'] === 'admin')` — hanya admin yang melihat menu Master Data
- **Controller:** `ManagementController` — semua method dilindungi `requireAdmin()`

---

## 💡 KESIMPULAN

Proyek ini adalah aplikasi **skripsi** yang menerapkan **COBIT 2019** untuk menilai tata kelola TI di **Desa Bogak Besar**. Arsitektur MVC custom dengan:

- **7 controller** sebagai pengatur alur
- **6 model** untuk akses database
- **13 file view** sebagai tampilan
- **1 router** (`core/App.php`) sebagai peta jalan
- **6 tabel database** yang saling berelasi

Aplikasi memungkinkan user untuk:
1. Memilih **responden** (aparatur desa) dan **proses TI** (DSS01/DSS02)
2. Memberikan **nilai 0-5** untuk setiap pertanyaan assessment
3. Menghitung **Capability Level** secara otomatis (rata-rata nilai)
4. Menghasilkan **rekomendasi perbaikan berbahasa Indonesia** yang spesifik untuk desa
5. Mencetak **laporan resmi** dengan format surat desa (lengkap logo dan tanda tangan)

**Rekomendasi yang dihasilkan** sangat kontekstual — mulai dari pembuatan SOP sederhana, buku ceklis harian, pengawasan oleh Kepala Desa, hingga alokasi anggaran TI dalam APBDes — sesuai dengan kondisi nyata Pemerintah Desa Bogak Besar.
