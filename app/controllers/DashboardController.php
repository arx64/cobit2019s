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

        // Ambil hasil analisis untuk ditampilkan di dashboard
        $results = $resultModel->getAll();

        // Data untuk grafik
        $chartLabels = [];
        $chartCurrent = [];
        $chartTarget = [];
        $chartGaps = [];

        foreach ($results as $result) {

            $chartLabels[] = $result['process_code'];

            $chartCurrent[] = (float)$result['capability_level'];

            $chartTarget[] = 4;

            $chartGaps[] = (float)$result['gap'];
        }
        
        require_once 'app/views/dashboard/index.php';
    }
}
