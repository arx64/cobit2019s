<?php
/**
 * Assessment Controller
 * Mengelola proses penilaian capability level
 */

require_once 'app/models/Process.php';
require_once 'app/models/Assessment.php';
require_once 'app/models/Result.php';

class AssessmentController {
    
    /**
     * Tampilkan halaman penilaian
     */
    public static function index() {
        $processModel = new Process();
        $assessmentModel = new Assessment();
        
        $processes = $processModel->getAll();
        
        // Ambil proses yang dipilih (default: DSS01)
        $selectedProcessId = isset($_GET['process']) ? intval($_GET['process']) : 1;
        $selectedProcess = $processModel->getById($selectedProcessId);
        
        // Ambil pertanyaan untuk proses terpilih
        $questions = $assessmentModel->getQuestionsByProcess($selectedProcessId);
        
        // Ambil jawaban yang sudah ada
        $answers = [];
        foreach ($questions as $question) {
            $answer = $assessmentModel->getAnswerByQuestion($question['id']);
            if ($answer) {
                $answers[$question['id']] = $answer['value'];
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
        
        if (empty($processId) || empty($answers)) {
            header('Location: index.php?page=data-penilaian&process=' . $processId . '&error=1');
            exit;
        }
        
        $assessmentModel = new Assessment();
        $resultModel = new Result();
        
        // Simpan setiap jawaban
        foreach ($answers as $questionId => $value) {
            $assessmentModel->saveAnswer([
                'question_id' => intval($questionId),
                'value' => intval($value)
            ]);
        }
        
        // Hitung capability level
        $capabilityLevel = $assessmentModel->calculateCapabilityLevel($processId);
        
        // Generate rekomendasi berdasarkan gap
        $recommendationData = $resultModel->generateRecommendation($capabilityLevel, 3);
        
        // Simpan hasil
        $resultModel->saveResult([
            'process_id' => $processId,
            'capability_level' => $capabilityLevel,
            'gap' => $recommendationData['gap'],
            'recommendation' => json_encode($recommendationData)
        ]);
        
        header('Location: index.php?page=data-penilaian&process=' . $processId . '&success=1');
        exit;
    }
    
    /**
     * AJAX: Ambil pertanyaan berdasarkan proses
     */
    public static function getQuestions() {
        header('Content-Type: application/json');
        
        $processId = isset($_GET['process_id']) ? intval($_GET['process_id']) : 0;
        
        if (empty($processId)) {
            echo json_encode(['error' => 'Process ID required']);
            exit;
        }
        
        $assessmentModel = new Assessment();
        $questions = $assessmentModel->getQuestionsByProcess($processId);
        
        // Ambil jawaban yang sudah ada
        foreach ($questions as &$question) {
            $answer = $assessmentModel->getAnswerByQuestion($question['id']);
            $question['current_value'] = $answer ? intval($answer['value']) : null;
        }
        
        echo json_encode($questions);
        exit;
    }
}
