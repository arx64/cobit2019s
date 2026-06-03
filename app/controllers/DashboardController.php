<?php
/**
 * Dashboard Controller
 * Mengelola halaman dashboard
 */

require_once 'app/models/User.php';
require_once 'app/models/Process.php';
require_once 'app/models/DesignFactor.php';
require_once 'app/models/Assessment.php';
require_once 'app/models/Result.php';

class DashboardController {
    
    /**
     * Tampilkan halaman dashboard
     */
    public static function index() {
        $userModel = new User();
        $processModel = new Process();
        $designFactorModel = new DesignFactor();
        $assessmentModel = new Assessment();
        $resultModel = new Result();
        
        // Ambil statistik
        $stats = [
            'total_users' => $userModel->countAll(),
            'total_processes' => $processModel->countAll(),
            'total_design_factors' => $designFactorModel->countAll(),
            'total_assessments' => $assessmentModel->countAssessments(),
            'avg_capability' => round($resultModel->getAverageCapabilityLevel(), 2),
            'total_gaps' => $resultModel->countGaps()
        ];

        // Hanya gunakan data penilaian untuk tanggal hari ini
        $today = date('Y-m-d');

        $processes = $processModel->getAll();

        $results = [];

        $chartLabels = [];
        $chartCurrent = [];
        $chartTarget = [];
        $chartGaps = [];

        foreach ($processes as $process) {
            $capability = $assessmentModel->calculateCapabilityLevel($process['id'], null, $today);
            $recommendation = $resultModel->generateRecommendation($capability, 4, $process['code'] ?? 'DSS01');

            $results[] = [
                'process' => $process,
                'process_code' => $process['code'],
                'process_name' => $process['name'],
                'capability_level' => $capability,
                'gap' => $recommendation['gap'],
                'recommendation' => $recommendation
            ];

            $chartLabels[] = $process['code'];
            $chartCurrent[] = (float)$capability;
            $chartTarget[] = 4;
            $chartGaps[] = (float)$recommendation['gap'];
        }
        // Update stats based on today's data
        $stats['total_assessments'] = $assessmentModel->countAssessmentsByDate($today);
        $stats['avg_capability'] = count($results) ? round(array_sum(array_column($results, 'capability_level')) / count($results), 2) : 0;
        $stats['total_gaps'] = 0;
        foreach ($results as $r) {
            $g = isset($r['gap']) ? floatval($r['gap']) : 0;
            if ($g > 0) $stats['total_gaps'] += $g;
        }

        require_once 'app/views/dashboard/index.php';
    }
}
