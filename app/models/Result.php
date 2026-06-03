<?php
/**
 * Result Model
 * Mengelola data hasil analisis dan rekomendasi
 */

require_once 'config/database.php';

class Result {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Ambil semua hasil analisis
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT r.*, p.code as process_code, p.name as process_name 
            FROM results r 
            JOIN processes p ON r.process_id = p.id 
            ORDER BY p.code ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Ambil hasil berdasarkan proses
     */
    public function getByProcess($processId) {
        $stmt = $this->db->prepare("
            SELECT r.*, p.code as process_code, p.name as process_name 
            FROM results r 
            JOIN processes p ON r.process_id = p.id 
            WHERE r.process_id = :process_id 
            LIMIT 1
        ");
        $stmt->execute(['process_id' => $processId]);
        return $stmt->fetch();
    }
    
    /**
     * Simpan atau update hasil analisis
     */
    public function saveResult($data) {
        // Cek apakah sudah ada hasil untuk proses ini
        $check = $this->db->prepare("SELECT id FROM results WHERE process_id = :process_id LIMIT 1");
        $check->execute(['process_id' => $data['process_id']]);
        
        if ($check->fetch()) {
            // Update hasil yang sudah ada
            $stmt = $this->db->prepare("
                UPDATE results 
                SET capability_level = :capability_level, gap = :gap, recommendation = :recommendation 
                WHERE process_id = :process_id
            ");
        } else {
            // Insert hasil baru
            $stmt = $this->db->prepare("
                INSERT INTO results (process_id, capability_level, gap, recommendation) 
                VALUES (:process_id, :capability_level, :gap, :recommendation)
            ");
        }
        
        return $stmt->execute([
            'process_id' => $data['process_id'],
            'capability_level' => $data['capability_level'],
            'gap' => $data['gap'],
            'recommendation' => $data['recommendation']
        ]);
    }
    
    /**
     * Hitung rata-rata capability level
     */
    public function getAverageCapabilityLevel() {
        $stmt = $this->db->query("SELECT AVG(capability_level) as avg_level FROM results");
        return $stmt->fetch()['avg_level'] ?? 0;
    }
    
    /**
     * Hitung total proses dengan gap
     */
    public function countGaps() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM results WHERE gap > 0");
        return $stmt->fetch()['total'];
    }
    
    /**
     * Generate rekomendasi berdasarkan gap
     */
    public function generateRecommendation($capabilityLevel, $targetLevel = 4, $processCode = 'DSS01') {
        $gap = $targetLevel - $capabilityLevel;

        // If meets or exceeds target
        if ($gap <= 0) {
            return [
                'gap' => 0,
                'level' => 'Optimal',
                'color' => 'success',
                'recommendations' => [
                    'Pertahankan capability level yang sudah tercapai',
                    'Lakukan monitoring dan evaluasi secara berkala',
                    'Dokumentasikan best practices yang sudah diterapkan'
                ]
            ];
        }

        $gapLevel = (int) ceil($gap);

        // DSS01 recommendation texts (user-provided)
        $dss01 = [
            1 => [
                'gap' => $gap,
                'level' => 'Minor Gap',
                'color' => 'info',
                'recommendations' => [
                    'Kegiatan operasional harian aparatur desa dalam memberikan pelayanan administrasi sudah berjalan lancar. Kelemahan utama terletak pada ketiadaan pedoman kerja tertulis. Pemerintah Desa wajib merumuskan Standar Operasional Prosedur resmi. Pedoman tersebut memuat rincian langkah kerja pasti. Aturan dasar mencakup tata cara aman menyalakan dan mematikan perangkat komputer, panduan merawat alat cetak, serta rutinitas pengecekan stabilitas jaringan internet setiap pagi. Ketersediaan panduan tertulis menjamin keseragaman mutu kerja seluruh pegawai. Operasional pelayanan publik di balai desa tidak boleh lagi bergantung pada kebiasaan lisan pegawai.'
                ]
            ],
            2 => [
                'gap' => $gap,
                'level' => 'Moderate Gap',
                'color' => 'warning',
                'recommendations' => [
                    'Pemeliharaan perangkat kerja dan inventaris komputer di balai desa belum berjalan secara rutin. Pemerintah Desa disarankan segera menyediakan Buku Ceklis Harian Fasilitas. Kepala Urusan Umum wajib ditugaskan memegang kendali pengawasan harian tersebut. Petugas wajib memeriksa kondisi fisik seluruh komputer setiap pagi sebelum loket pelayanan dibuka. Pemeriksaan mencakup ketersediaan tinta alat cetak, keamanan kabel jaringan, dan pengujian kesiapan fasilitas daya cadangan seperti UPS atau genset. Pengecekan harian terstruktur akan mencegah kerusakan perangkat secara mendadak. Antisipasi dini menekan risiko terhentinya pelayanan administrasi bagi masyarakat.'
                ]
            ],
            3 => [
                'gap' => $gap,
                'level' => 'Major Gap',
                'color' => 'warning',
                'recommendations' => [
                    'Pimpinan instansi, dalam hal ini Kepala Desa, harus mulai berperan aktif untuk mengawasi dan mengevaluasi rutinitas penggunaan fasilitas kerja. Pekerjaan operasional tidak bisa lagi dibiarkan berjalan sendiri tanpa adanya pengawasan yang terukur. Pimpinan perlu melakukan evaluasi secara berkala untuk memastikan apakah perawatan perangkat keras sudah dijalankan sesuai dengan pedoman tertulis yang telah disepakati, serta menilai apakah seluruh staf sudah memiliki kedisiplinan yang tinggi dalam menjaga aset-aset milik instansi.'
                ]
            ],
            4 => [
                'gap' => $gap,
                'level' => 'Major Gap',
                'color' => 'danger',
                'recommendations' => [
                    'Pemantauan kelayakan fasilitas pelayanan belum terintegrasi ke dalam program aplikasi terpusat. Pemerintah Desa sangat disarankan mengimplementasikan modul aplikasi khusus guna mengelola jadwal pemeliharaan seluruh perangkat elektronik. Pemanfaatan teknologi digital memungkinkan manajemen memantau rekam jejak kondisi setiap perangkat keras secara presisi. Program pencatatan terkomputerisasi mampu memproyeksikan sisa umur pakai perangkat infrastruktur secara akurat. Transformasi digital mempercepat deteksi gejala kerusakan perangkat sebelum proses pelayanan publik mengalami hambatan.'
                ]
            ],
            5 => [
                'gap' => $gap,
                'level' => 'Major Gap',
                'color' => 'danger',
                'recommendations' => [
                    'Tata kelola dasar terkait pemeliharaan fasilitas teknologi informasi di balai desa belum terbentuk secara terstruktur. Pemerintah Desa wajib merancang ulang tata kelola infrastruktur dengan mengalokasikan anggaran khusus ke dalam Rencana Anggaran Pendapatan dan Belanja Desa. Kepala Desa berkewajiban menerbitkan Surat Keputusan formal guna menetapkan aparatur desa khusus sebagai penanggung jawab fasilitas teknologi. Aparatur pemegang wewenang tersebut memikul tugas operasional harian menjaga kelayakan fungsi komputer dan kontinuitas jaringan internet. Perancangan ulang fondasi tata kelola memastikan keberlanjutan dukungan teknologi terhadap pelayanan desa.'
                ]
            ]
        ];

        // DSS02 recommendation texts (user-provided)
        $dss02 = [
            1 => [
                'gap' => $gap,
                'level' => 'Minor Gap',
                'color' => 'info',
                'recommendations' => [
                    'Ketanggapan aparatur desa menghadapi gangguan teknologi informasi menunjukkan kapasitas memadai. Kekurangan fundamental terletak pada ketiadaan dokumen tertulis pengatur alur pelaporan insiden. Pemerintah Desa perlu menetapkan Standar Operasional Prosedur Penanganan Insiden. Dokumen ini mengatur instruksi pelaporan saat terjadi penurunan kinerja komputer, kemacetan alat cetak, atau pemutusan koneksi internet. Mekanisme pelaporan tertulis memastikan setiap pengaduan didistribusikan langsung kepada pihak pemegang kewenangan teknis. Kejelasan alur komunikasi memfasilitasi percepatan proses pemulihan layanan operasional balai desa.'
                ]
            ],
            2 => [
                'gap' => $gap,
                'level' => 'Moderate Gap',
                'color' => 'warning',
                'recommendations' => [
                    'Identifikasi kapabilitas operasional menunjukkan ketiadaan rekam jejak pendokumentasian kerusakan perangkat infrastruktur kerja. Pemerintah Desa diwajibkan menyusun dan menempatkan Buku Riwayat Insiden secara khusus di pusat layanan administrasi. Aparatur desa wajib mencatat detail waktu kejadian insiden, identifikasi penyebab teknis, dan tahapan perbaikan yang diimplementasikan. Dokumen riwayat kerusakan berfungsi sebagai basis pengetahuan utama bagi tata kelola instansi. Aparatur desa dapat merujuk catatan historis tersebut untuk mengaplikasikan teknik pemulihan tepat saat menghadapi insiden teknis serupa di masa mendatang.'
                ]
            ],
            3 => [
                'gap' => $gap,
                'level' => 'Major Gap',
                'color' => 'warning',
                'recommendations' => [
                    'Proses penyelesaian insiden teknis harian oleh aparatur desa belum diukur menggunakan standar waktu pemulihan pasti. Sekretaris Desa memiliki kewajiban manajerial menetapkan batas toleransi waktu penyelesaian gangguan bagi setiap komponen infrastruktur. Pemerintah Desa harus mengesahkan regulasi yang mewajibkan penyelesaian kendala jaringan internet maksimal satu jam dan perbaikan alat cetak maksimal tiga puluh menit. Penetapan target waktu pemulihan berfungsi sebagai indikator kinerja operasional aparatur desa.'
                ]
            ],
            4 => [
                'gap' => $gap,
                'level' => 'Major Gap',
                'color' => 'danger',
                'recommendations' => [
                    'Mekanisme pengaduan kerusakan fasilitas teknologi belum menggunakan fitur pelaporan terpadu. Pemerintah Desa direkomendasikan mengintegrasikan fitur pelaporan insiden digital ke dalam aplikasi balai desa. Seluruh aparatur wajib mendata kejadian kerusakan aset melalui program digital tersebut guna memfasilitasi pengawasan manajerial secara langsung. Perangkat keras yang telah direstorasi wajib melewati tahap verifikasi kelayakan operasional oleh pegawai pelapor. Penutupan status tiket pengaduan hanya diizinkan setelah perangkat divalidasi mampu beroperasi secara optimal kembali.'
                ]
            ],
            5 => [
                'gap' => $gap,
                'level' => 'Major Gap',
                'color' => 'danger',
                'recommendations' => [
                    'Tingkat kesiapan instansi desa dalam memitigasi insiden kelumpuhan teknologi berskala masif masih berada pada level rentan. Kelumpuhan masif mencakup skenario kerusakan total perangkat komputer pelayanan atau pemutusan jaringan internet secara teritorial. Pemerintah Desa diwajibkan menyusun dokumen rencana kerja darurat guna menjamin keberlanjutan layanan administrasi masyarakat. Instansi harus menyediakan pos anggaran tak terduga khusus pemulihan infrastruktur teknologi. Kepala Desa direkomendasikan mengesahkan nota kesepahaman formal dengan penyedia jasa teknisi eksternal guna menjamin ketersediaan tenaga ahli profesional saat kondisi darurat.'
                ]
            ]
        ];

        $pool = (strtoupper($processCode) === 'DSS02' || strtoupper($processCode) === 'DSS02') ? $dss02 : $dss01;

        if (isset($pool[$gapLevel])) {
            return $pool[$gapLevel];
        }

        // Default: highest severity if gap level exceeds defined
        return $pool[5] ?? [
            'gap' => $gap,
            'level' => 'Major Gap',
            'color' => 'danger',
            'recommendations' => ['Silakan evaluasi dan susun rencana perbaikan menyeluruh']
        ];
    }
}
