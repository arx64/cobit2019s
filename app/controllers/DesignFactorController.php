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
                'village_context' => [
                    'Ketersediaan layanan TI 24/7',
                    'Keamanan data dan informasi',
                    'Kepatuhan terhadap regulasi',
                    'Efisiensi penggunaan infrastruktur TI',
                    'Inovasi dalam pelayanan publik'
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
                'village_risks' => [
                    'Gangguan layanan TI saat jam operasional',
                    'Hilangnya data akibat corrupt database',
                    'Akses tidak sah terhadap data dan informasi',
                    'Kerusakan hardware dan infrastruktur TI',
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
                'village_issues' => [
                    'Keterbatasan anggaran TI',
                    'Ketergantungan pada pengembang aplikasi',
                    'Sistem lama yang belum terintegrasi',
                    'Kurangnya tenaga TI profesional',
                    'Volume data yang terus bertambah'
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
                'village_it_role' => 'Dalam konteks desa, TI umumnya berperan sebagai supporter dan driver untuk:
                    <ul>
                        <li>Mendukung proses administrasi perkantoran</li>
                        <li>Memfasilitasi pelayanan publik berbasis digital</li>
                        <li>Meningkatkan efisiensi proses pelayanan</li>
                        <li>Mendorong transparansi informasi publik</li>
                    </ul>'
            ]
        ];
        
        require_once 'app/views/design-factor/index.php';
    }
}
