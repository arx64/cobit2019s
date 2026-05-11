<?php
/**
 * Recommendation Controller
 * Mengelola hasil analisis dan rekomendasi
 */

require_once 'app/models/Process.php';
require_once 'app/models/Result.php';
require_once 'app/models/Assessment.php';

class RecommendationController {
    
    /**
     * Tampilkan halaman hasil analisis dan rekomendasi
     */
    public static function index() {
        $processModel = new Process();
        $resultModel = new Result();
        $assessmentModel = new Assessment();
        
        // Ambil semua proses
        $processes = $processModel->getAll();
        
        // Ambil hasil analisis untuk setiap proses
        $results = [];
        foreach ($processes as $process) {
            // Hitung capability level terkini
            $capabilityLevel = $assessmentModel->calculateCapabilityLevel($process['id']);
            
            // Generate rekomendasi
            $recommendationData = $resultModel->generateRecommendation($capabilityLevel, 3);
            
            // Simpan/update hasil
            $resultModel->saveResult([
                'process_id' => $process['id'],
                'capability_level' => $capabilityLevel,
                'gap' => $recommendationData['gap'],
                'recommendation' => json_encode($recommendationData)
            ]);
            
            $results[] = [
                'process' => $process,
                'capability_level' => $capabilityLevel,
                'recommendation' => $recommendationData
            ];
        }
        
        // Hitung statistik keseluruhan
        $totalProcesses = count($results);
        $avgCapability = $totalProcesses > 0 ? 
            array_sum(array_column($results, 'capability_level')) / $totalProcesses : 0;
        $totalGaps = array_sum(array_column(array_column($results, 'recommendation'), 'gap'));
        
        // Identifikasi proses dengan gap terbesar
        usort($results, function($a, $b) {
            return $b['recommendation']['gap'] - $a['recommendation']['gap'];
        });
        
        $priorityProcess = $results[0] ?? null;
        
        // Re-sort berdasarkan kode proses
        usort($results, function($a, $b) {
            return strcmp($a['process']['code'], $b['process']['code']);
        });
        
        // Capability level target
        $targetLevel = 4;
        
        require_once 'app/views/recommendation/index.php';
    }
}
