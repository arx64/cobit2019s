# Sistem Analisis Risiko TI berbasis COBIT 2019

Aplikasi web untuk melakukan penilaian capability level, analisis kesenjangan (gap analysis), dan menampilkan rekomendasi perbaikan berdasarkan framework COBIT 2019. Sistem ini dikembangkan sebagai alat bantu analisis untuk sistem e-Raport di lingkungan sekolah.

## Fitur Sistem

### 1. Sistem Autentikasi
- Login dengan session-based authentication
- Password hashing dengan bcrypt
- Role-based access (Admin & User)
- Logout dengan session destruction

### 2. Dashboard
- Ringkasan statistik sistem
- Jumlah proses, penilaian, capability level, dan gap
- Quick action menu
- Progress penilaian real-time

### 3. Framework COBIT
- Informasi proses DSS01 (Manage Operations)
- Informasi proses DSS05 (Manage Security Services)
- Penjelasan komponen dan praktik kunci
- Referensi skala capability level

### 4. Design Factor
- DF02: Enterprise Goals
- DF03: Risk Profile
- DF04: I&T Related Issues
- DF06: Role of IT
- Konteks implementasi di sekolah dan e-Raport

### 5. Data Penilaian
- Form penilaian berbasis indikator
- Skala penilaian 0-5 (COBIT Capability Level)
- Penyimpanan hasil penilaian
- Progress tracking

### 6. Rekomendasi
- Hasil capability level per proses
- Gap analysis dengan target level 3
- Rekomendasi perbaikan yang terstruktur
- Rencana tindak lanjut jangka pendek, menengah, dan panjang

## Teknologi

- **Backend**: PHP Native (tanpa framework)
- **Database**: MySQL dengan PDO prepared statements
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 5
- **Icons**: Bootstrap Icons
- **Arsitektur**: MVC (Model-View-Controller) sederhana

## Struktur Folder

```
cobit2019/
├── app/
│   ├── controllers/      # Controller files
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── FrameworkController.php
│   │   ├── DesignFactorController.php
│   │   ├── AssessmentController.php
│   │   └── RecommendationController.php
│   ├── models/           # Model files
│   │   ├── User.php
│   │   ├── Process.php
│   │   ├── DesignFactor.php
│   │   ├── Assessment.php
│   │   └── Result.php
│   └── views/            # View files
│       ├── layouts/
│       │   └── main.php
│       ├── auth/
│       │   └── login.php
│       ├── dashboard/
│       │   └── index.php
│       ├── framework/
│       │   └── index.php
│       ├── design-factor/
│       │   └── index.php
│       ├── assessment/
│       │   └── index.php
│       ├── recommendation/
│       │   └── index.php
│       └── errors/
│           └── 404.php
├── config/
│   └── database.php      # Konfigurasi database
├── core/
│   └── App.php           # Router dan App core
├── public/
│   └── assets/
│       ├── css/
│       │   ├── style.css
│       │   └── login.css
│       ├── js/
│       │   └── main.js
│       └── img/
├── database.sql          # Database schema
├── index.php             # Front controller
└── README.md             # Dokumentasi
```

## Cara Install

### 1. Persyaratan Sistem
- PHP >= 7.4
- MySQL >= 5.7 atau MariaDB >= 10.2
- Web Server (Apache/Nginx)

### 2. Installasi

1. **Clone atau download repository**
   ```bash
   git clone https://github.com/username/cobit2019.git
   cd cobit2019
   ```

2. **Import database**
   ```bash
   mysql -u root -p < database.sql
   ```
   
   Atau import melalui phpMyAdmin:
   - Buka phpMyAdmin
   - Buat database baru: `cobit2019_assessment`
   - Import file `database.sql`

3. **Konfigurasi database**
   Edit file `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'cobit2019_assessment');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // sesuaikan dengan password MySQL Anda
   ```

4. **Akses aplikasi**
   Buka browser dan akses:
   ```
   http://localhost/cobit2019/
   ```

### 3. Login Default

- **Email**: `admin@cobit.com`
- **Password**: `password`

## Routing URL

Sistem menggunakan front controller pattern dengan URL:
```
index.php?page=[nama_halaman]
```

### Daftar Halaman:
- `index.php?page=login` - Halaman login
- `index.php?page=dashboard` - Dashboard utama
- `index.php?page=framework` - Framework COBIT
- `index.php?page=design-factor` - Design Factor
- `index.php?page=data-penilaian` - Data Penilaian
- `index.php?page=rekomendasi` - Hasil & Rekomendasi
- `index.php?page=logout` - Logout

## Skala Capability Level COBIT

| Level | Label | Deskripsi |
|-------|-------|-----------|
| 0 | Incomplete | Praktik tidak dilaksanakan |
| 1 | Performed | Praktik baru mulai diterapkan |
| 2 | Managed | Praktik terdokumentasi dan dipantau |
| 3 | Established | Praktik standar dan terintegrasi |
| 4 | Predictable | Praktik terukur dan dikendalikan |
| 5 | Optimizing | Praktik terus diperbaiki |

## Keamanan

- **Password Hashing**: Menggunakan `password_hash()` dengan algoritma bcrypt
- **Prepared Statements**: Semua query database menggunakan PDO prepared statements
- **Session Security**: Session regeneration dan secure session handling
- **XSS Protection**: Output escaping dengan `htmlspecialchars()`
- **CSRF Protection**: Dapat ditambahkan sesuai kebutuhan

## Pengembangan Selanjutnya

Fitur yang dapat ditambahkan:
- [ ] Manajemen pengguna (CRUD)
- [ ] Export laporan ke PDF/Excel
- [ ] Grafik dan visualisasi data
- [ ] Multi-user dengan berbagai role
- [ ] Riwayat perubahan penilaian
- [ ] Notifikasi dan reminder
- [ ] API untuk integrasi dengan sistem lain

## Lisensi

Proyek ini dibuat untuk keperluan akademik dan dapat digunakan secara bebas dengan tetap mencantumkan atribusi.

## Kontak

Untuk pertanyaan atau saran, silakan hubungi:
- Email: admin@cobit.com

---

**Catatan**: Sistem ini dikembangkan sebagai alat bantu analisis dan dapat disesuaikan dengan kebutuhan spesifik institusi pendidikan.
