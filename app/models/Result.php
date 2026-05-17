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
    public function generateRecommendation($capabilityLevel, $targetLevel = 4) {
        $gap = $targetLevel - $capabilityLevel;
        
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
        
        if ($gap <= 1) {
            return [
                'gap' => $gap,
                'level' => 'Minor Gap',
                'color' => 'info',
                'recommendations' => [
                    'Tingkatkan implementasi praktik terbaik',
                    'Perkuat dokumentasi proses',
                    'Lakukan pelatihan untuk tim IT',
                    'Evaluasi dan perbaiki prosedur yang ada'
                ]
            ];
        }
        
        if ($gap <= 2) {
            return [
                'gap' => $gap,
                'level' => 'Moderate Gap',
                'color' => 'warning',
                'recommendations' => [
                    'Susun rencana perbaikan komprehensif',
                    'Alokasikan sumber daya yang memadai',
                    'Implementasi tools dan teknologi pendukung',
                    'Bangun tim yang lebih terstruktur',
                    'Dokumentasikan seluruh proses bisnis'
                ]
            ];
        }
        
        return [
            'gap' => $gap,
            'level' => 'Major Gap',
            'color' => 'danger',
            'recommendations' => [
                'Lakukan transformasi proses fundamental',
                'Susun roadmap perbaikan jangka panjang',
                'Pertimbangkan outsourcing untuk kompetensi yang kurang',
                'Lakukan pelatihan intensif untuk seluruh tim',
                'Implementasi framework governance yang komprehensif',
                'Evaluasi ulang struktur organisasi IT'
            ]
        ];
    }
}
