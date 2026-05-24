<?php
/**
 * Assessment Controller
 * Mengelola proses penilaian capability level
 */

require_once 'app/models/Process.php';
require_once 'app/models/Respondent.php';
require_once 'app/models/Assessment.php';
require_once 'app/models/Result.php';

class AssessmentController {
    
    /**
     * Tampilkan halaman penilaian
     */
    public static function index() {
        $processModel = new Process();
        $respondentModel = new Respondent();
        $assessmentModel = new Assessment();
        
        $processes = $processModel->getAll(true);
        $respondents = $respondentModel->getAll();
        
        // Ambil proses yang dipilih (default: DSS01)
        $selectedProcessId = isset($_GET['process']) ? intval($_GET['process']) : 1;
        $selectedProcess = $processModel->getById($selectedProcessId);

        // Ambil responden yang dipilih
        $selectedRespondentId = isset($_GET['respondent_id']) ? intval($_GET['respondent_id']) : 0;
        $selectedRespondent = $selectedRespondentId ? $respondentModel->getById($selectedRespondentId) : null;
        // $selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
        $today = date('Y-m-d');

        $selectedDate = isset($_GET['date']) && !empty($_GET['date'])
            ? $_GET['date']
            : $today;

        // Prevent future date
        if ($selectedDate > $today) {
            $selectedDate = $today;
        }
        $assessmentDates = $selectedRespondent ? $assessmentModel->getAssessmentDatesByRespondent($selectedRespondentId) : [];
        
        $questions = [];
        $answers = [];
        if ($selectedRespondent) {
            $questions = $assessmentModel->getQuestionsByProcess($selectedProcessId);
            foreach ($questions as $question) {
                $answer = $assessmentModel->getAnswerByQuestion($question['id'], $selectedRespondentId, $selectedDate ?: null);
                if ($answer) {
                    $answers[$question['id']] = $answer['value'];
                }
            }
        }
        
        // Skala penilaian COBIT
        $ratingScale = [
            0 => ['label' => 'Tidak Dilakukan', 'desc' => 'Praktik tidak dilaksanakan'],
            1 => ['label' => 'Inisialisasi', 'desc' => 'Praktik baru mulai diterapkan'],
            2 => ['label' => 'TerKelola', 'desc' => 'Praktik terdokumentasi dan dilaksanakan'],
            3 => ['label' => 'Terdefinisi', 'desc' => 'Praktik standar dan terintegrasi'],
            4 => ['label' => 'Terukur', 'desc' => 'Praktik terukur dan dikendalikan'],
            5 => ['label' => 'Optimasi', 'desc' => 'Praktik terus diperbaiki']
        ];
        
        // Status penilaian
        $success = isset($_GET['success']) ? true : false;
        
        require_once 'app/views/assessment/index.php';
    }
    
    /**
     * Simpan hasil penilaian
     */
    public static function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=data-penilaian');
            exit;
        }
        
        $processId = isset($_POST['process_id']) ? intval($_POST['process_id']) : 0;
        $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
        $today = date('Y-m-d');

        // Tidak boleh tanggal masa depan
        if ($date > $today) {
            header('Location: index.php?page=data-penilaian&error=future_date');
            exit;
        }
        
        $respondentId = isset($_POST['respondent_id']) ? intval($_POST['respondent_id']) : 0;
        if (empty($processId) || empty($respondentId) || empty($answers)) {
            header('Location: index.php?page=data-penilaian&process=' . $processId . '&respondent_id=' . $respondentId . '&error=1');
            exit;
        }
        
        $assessmentModel = new Assessment();
        $resultModel = new Result();
        
        // Simpan setiap jawaban
        foreach ($answers as $questionId => $value) {
            $intValue = intval($value);
            if ($intValue < 0 || $intValue > 5) {
                continue;
            }

            $assessmentModel->saveAnswer([
                'question_id' => intval($questionId),
                'respondent_id' => $respondentId,
                'assessment_date' => $date,
                'value' => $intValue
            ]);
        }
        
        // Hitung capability level untuk responden dan tanggal yang dipilih
        $capabilityLevel = $assessmentModel->calculateCapabilityLevel($processId, $respondentId, $date);
        error_log(sprintf('[ASSESSMENT] process=%d respondent=%d date=%s capability=%s', $processId, $respondentId, $date, $capabilityLevel));
        
        // Generate rekomendasi berdasarkan gap
        $recommendationData = $resultModel->generateRecommendation($capabilityLevel, 4);
        
        // Simpan hasil
        $resultModel->saveResult([
            'process_id' => $processId,
            'capability_level' => $capabilityLevel,
            'gap' => $recommendationData['gap'],
            'recommendation' => json_encode($recommendationData)
        ]);
        
        $selectedDate = isset($_POST['date']) ? $_POST['date'] : '';
        $dateParam = $selectedDate ? '&date=' . urlencode($selectedDate) : '';
        header('Location: index.php?page=data-penilaian&process=' . $processId . '&respondent_id=' . $respondentId . $dateParam . '&success=1');
        exit;
    }
    
    /**
     * AJAX: Ambil pertanyaan berdasarkan proses
     */
    public static function getQuestions() {
        header('Content-Type: application/json');
        
        $processId = isset($_GET['process_id']) ? intval($_GET['process_id']) : 0;
        $respondentId = isset($_GET['respondent_id']) ? intval($_GET['respondent_id']) : 0;
        
        if (empty($processId) || empty($respondentId)) {
            echo json_encode(['error' => 'Process ID and respondent ID required']);
            exit;
        }
        
        $assessmentModel = new Assessment();
        $questions = $assessmentModel->getQuestionsByProcess($processId);
        // $selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
        $today = date('Y-m-d');

        $selectedDate = isset($_GET['date']) && !empty($_GET['date'])
            ? $_GET['date']
            : $today;

        // Prevent future date
        if ($selectedDate > $today) {
            $selectedDate = $today;
        }
        
        // Ambil jawaban yang sudah ada untuk responden
        foreach ($questions as &$question) {
            $answer = $assessmentModel->getAnswerByQuestion($question['id'], $respondentId, $selectedDate ?: null);
            $question['current_value'] = $answer ? intval($answer['value']) : null;
        }
        
        echo json_encode($questions);
        exit;
    }
}
