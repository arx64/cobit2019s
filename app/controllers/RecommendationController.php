<?php
/**
 * Recommendation Controller
 * Mengelola hasil analisis dan rekomendasi
 */

require_once 'app/models/Process.php';
require_once 'app/models/Result.php';
require_once 'app/models/Assessment.php';
require_once 'app/models/Respondent.php';

class RecommendationController {
    
    /**
     * Tampilkan halaman hasil analisis dan rekomendasi
     */
    public static function index()
    {

        $processModel = new Process();
        $resultModel = new Result();
        $assessmentModel = new Assessment();

        // Ambil tanggal dari parameter atau gunakan hari ini
        $today = date('Y-m-d');
        $selectedDate = isset($_GET['date']) && !empty($_GET['date'])
            ? $_GET['date']
            : $today;
        $selectedTab = isset($_GET['tab']) ? $_GET['tab'] : 'all';

        // Ambil process filter
        $selectedProcessId = isset($_GET['process'])
            ? intval($_GET['process'])
            : 0;

        $availableDates = $assessmentModel->getAssessmentDates();

        /**
         * Jika ada process tertentu:
         * ambil hanya process itu
         */
        if ($selectedProcessId > 0) {

            $process = $processModel->getById($selectedProcessId);

            $processes = $process ? [$process] : [];
        } else {

            // Ambil semua proses
            $processes = $processModel->getAll();
        }

        // Ambil hasil analisis
        $results = [];

        foreach ($processes as $process) {

            // Hitung capability level
            $capabilityLevel = $assessmentModel
                ->calculateCapabilityLevel(
                    $process['id'],
                    null,
                    $selectedDate ?: null
                );

            // Generate rekomendasi (pass process code so DSS01/DSS02 have distinct texts)
            $recommendationData = $resultModel
                ->generateRecommendation(
                    $capabilityLevel,
                    4,
                    $process['code'] ?? 'DSS01'
                );

            // Simpan hasil hanya jika tidak sedang menampilkan hasil per tanggal
            if (empty($selectedDate)) {
                $resultModel->saveResult([
                    'process_id' => $process['id'],
                    'capability_level' => $capabilityLevel,
                    'gap' => $recommendationData['gap'],
                    'recommendation' => json_encode($recommendationData)
                ]);
            }

            $results[] = [
                'process' => $process,
                'capability_level' => $capabilityLevel,
                'recommendation' => $recommendationData
            ];
        }

        // Statistik
        $totalProcesses = count($results);

        $avgCapability = $totalProcesses > 0
            ? array_sum(array_column($results, 'capability_level')) / $totalProcesses
            : 0;

        // Jumlahkan hanya gap positif (abaikan nilai negatif/0)
        $totalGaps = 0;
        foreach ($results as $r) {
            $gap = isset($r['recommendation']['gap']) ? floatval($r['recommendation']['gap']) : 0;
            if ($gap > 0) {
                $totalGaps += $gap;
            }
        }

        // Priority process
        usort($results, function ($a, $b) {
            return $b['recommendation']['gap']
                - $a['recommendation']['gap'];
        });

        $priorityProcess = $results[0] ?? null;

        // Sort kembali
        usort($results, function ($a, $b) {
            return strcmp(
                $a['process']['code'],
                $b['process']['code']
            );
        });

        $targetLevel = 4;
        $selectedProcessId = isset($_GET['process'])
            ? intval($_GET['process'])
            : 0;

        // Ambil jumlah responden berdasarkan tanggal yang dipilih
        require_once 'config/database.php';
        $db = getDB();

        $stmt = $db->prepare("
    SELECT DISTINCT respondent_id
    FROM assessment_answers
    WHERE assessment_date = ?
");

        $stmt->execute([$selectedDate]);
        $respondents = $stmt->fetchAll();

        $totalRespondents = count($respondents);

        $respondentPosition = 'Operator Sistem';

        if ($totalRespondents = 3) {

            $respondentPosition = 'Perangkat Desa';
        } elseif ($totalRespondents > 0) {

            $respondentModel = new Respondent();

            $respondent = $respondentModel->getById(
                $respondents[0]['respondent_id']
            );

            if ($respondent && !empty($respondent['position'])) {
                $respondentPosition = $respondent['position'];
            }
        }
        
        require_once 'app/views/recommendation/index.php';
    }
    public static function dss01()
    {
        $_GET['process'] = 1;
        self::index();
    }

    public static function dss02()
    {
        $_GET['process'] = 2;
        self::index();
    }
}
