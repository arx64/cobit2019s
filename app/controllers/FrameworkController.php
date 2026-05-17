<?php
/**
 * Framework Controller
 * Mengelola informasi proses COBIT
 */

require_once 'app/models/Process.php';

class FrameworkController {
    
    /**
     * Tampilkan daftar proses COBIT
     */
    public static function index() {
        $processModel = new Process();
        $processes = $processModel->getAll();
        
        // Deskripsi proses DSS01 dan DSS05
        $processDescriptions = [
            'DSS01' => [
                'title' => 'Manage Operations',
                'purpose' => 'Mengelola operasional TI untuk memastikan kelangsungan layanan teknologi informasi secara efektif dan efisien.',
                'components' => [
                    'DSS01.01 - Manage operations',
                    'DSS01.02 - Manage service requests and incidents',
                    'DSS01.03 - Manage problems',
                    'DSS01.04 - Manage continuity',
                    'DSS01.05 - Manage security services',
                    'DSS01.06 - Manage business process controls'
                ],
                'practices' => [
                    'Perencanaan dan penjadwalan operasional',
                    'Monitoring kinerja layanan TI',
                    'Manajemen insiden dan permintaan layanan',
                    'Manajemen kontinuitas layanan',
                    'Manajemen kontrol proses bisnis'
                ]
            ],
            'DSS05' => [
                'title' => 'Manage Security Services',
                'purpose' => 'Memastikan keamanan sistem dan data melalui implementasi kebijakan, prosedur, dan kontrol keamanan yang efektif.',
                'components' => [
                    'DSS05.01 - Manage security operations',
                    'DSS05.02 - Manage identity and access',
                    'DSS05.03 - Manage data security',
                    'DSS05.04 - Manage network security',
                    'DSS05.05 - Manage security monitoring',
                    'DSS05.06 - Manage security incidents'
                ],
                'practices' => [
                    'Implementasi kebijakan keamanan',
                    'Manajemen identitas dan akses',
                    'Proteksi data dan informasi',
                    'Keamanan infrastruktur jaringan',
                    'Monitoring dan deteksi ancaman',
                    'Respons insiden keamanan'
                ]
            ]
        ];
        
        require_once 'app/views/framework/index.php';
    }

    /**
     * Tampilkan halaman Proses DSS
     */
    public static function proses() {
        require_once 'app/views/framework/proses.php';
    }
}
