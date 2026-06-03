<?php
/**
 * Design Factor Controller
 * Mengelola informasi faktor desain COBIT
 */

require_once 'app/models/DesignFactor.php';

class DesignFactorController {
    
    /**
     * Tampilkan daftar design factors COBIT
     */
    public static function index() {
        $designFactorModel = new DesignFactor();
        $designFactors = $designFactorModel->getAll();
        
        // Detail design factors yang digunakan
        $dfDetails = [
            'DF02' => [
                'title' => 'Enterprise Goals',
                'description' => 'Faktor desain yang berkaitan dengan tujuan strategis organisasi dan bagaimana TI mendukung pencapaian tujuan tersebut.',
                'focus_areas' => [
                    'Penyampaian layanan sesuai komitmen',
                    'Keamanan informasi dan privasi',
                    'Kepatuhan regulasi dan standard',
                    'Optimasi sumber daya TI',
                    'Transformasi digital'
                ],
                'e_raport_context' => [
                    'Ketersediaan sistem e-Raport 24/7',
                    'Keamanan data nilai siswa',
                    'Kepatuhan terhadap standar pendidikan',
                    'Efisiensi penggunaan infrastruktur TI sekolah',
                    'Inovasi dalam proses penilaian'
                ]
            ],
            'DF03' => [
                'title' => 'Risk Profile',
                'description' => 'Profil risiko organisasi yang mencakup identifikasi, analisis, dan evaluasi risiko terkait TI.',
                'risk_categories' => [
                    'Risiko ketersediaan sistem',
                    'Risiko kehilangan data',
                    'Risiko akses tidak sah',
                    'Risiko kegagalan infrastruktur',
                    'Risiko bencana dan gangguan'
                ],
                'e_raport_risks' => [
                    'Server e-Raport down saat waktu penginputan nilai',
                    'Hilangnya data nilai akibat corrupt database',
                    'Akses guru/wali kelas yang tidak berwenang',
                    'Kerusakan hardware server',
                    'Kehilangan data akibat bencana alam'
                ]
            ],
            'DF04' => [
                'title' => 'I&T Related Issues',
                'description' => 'Isu-isu terkait Teknologi Informasi dan Teknologi yang mempengaruhi desain governance system.',
                'common_issues' => [
                    'Keterbatasan sumber daya TI',
                    'Ketergantungan pada vendor tertentu',
                    'Legacy systems yang sulit diintegrasikan',
                    'Keterbatasan kompetensi SDM TI',
                    'Pertumbuhan data yang eksponensial'
                ],
                'e_raport_issues' => [
                    'Keterbatasan anggaran TI sekolah',
                    'Ketergantungan pada pengembang aplikasi',
                    'Sistem lama yang belum terintegrasi',
                    'Kurangnya tenaga TI profesional',
                    'Volume data siswa yang terus bertambah'
                ]
            ],
            'DF06' => [
                'title' => 'Role of IT',
                'description' => 'Peran TI dalam organisasi yang menentukan bagaimana TI dikelola dan di-govern.',
                'it_roles' => [
                    'IT sebagai supporter - mendukung operasional',
                    'IT sebagai driver - mendorong inovasi',
                    'IT sebagai partner - kolaborasi strategis',
                    'IT sebagai transformer - transformasi bisnis'
                ],
                'school_it_role' => 'Dalam konteks sekolah, TI umumnya berperan sebagai supporter dan driver untuk:
                    <ul>
                        <li>Mendukung proses administrasi akademik</li>
                        <li>Memfasilitasi pembelajaran digital</li>
                        <li>Meningkatkan efisiensi proses penilaian</li>
                        <li>Mendorong transparansi informasi pendidikan</li>
                    </ul>'
            ]
        ];
        
        require_once 'app/views/design-factor/index.php';
    }
}
