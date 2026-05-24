<?php
/**
 * Management Controller
 * Mengelola CRUD master data domain/proses dan pertanyaan assessment
 */

require_once 'app/models/Process.php';
require_once 'app/models/Assessment.php';

class ManagementController {
    private static function requireAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=dashboard&error=forbidden');
            exit;
        }
    }

    public static function processes() {
        self::requireAdmin();

        $processModel = new Process();
        $processes = $processModel->getAll();
        $editProcess = null;

        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $editProcess = $processModel->getById(intval($_GET['id']));
        }

        require_once 'app/views/admin/processes.php';
    }

    public static function saveProcess() {
        self::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=processes');
            exit;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($code === '' || $name === '') {
            header('Location: index.php?page=processes&error=missing_fields');
            exit;
        }

        $processModel = new Process();
        if ($id > 0) {
            $processModel->update([
                'id' => $id,
                'code' => strtoupper($code),
                'name' => $name,
                'description' => $description,
                'is_active' => $isActive
            ]);
        } else {
            $processModel->create([
                'code' => strtoupper($code),
                'name' => $name,
                'description' => $description,
                'is_active' => $isActive
            ]);
        }

        header('Location: index.php?page=processes&success=1');
        exit;
    }

    public static function deleteProcess() {
        self::requireAdmin();

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=processes');
            exit;
        }

        $processModel = new Process();
        $processModel->delete(intval($_GET['id']));

        header('Location: index.php?page=processes&deleted=1');
        exit;
    }

    public static function toggleProcess() {
        self::requireAdmin();

        if (!isset($_GET['id']) || !isset($_GET['value'])) {
            header('Location: index.php?page=processes');
            exit;
        }

        $processModel = new Process();
        $processModel->toggleActive(intval($_GET['id']), intval($_GET['value']));

        header('Location: index.php?page=processes&success=1');
        exit;
    }

    public static function questions() {
        self::requireAdmin();

        $assessmentModel = new Assessment();
        $processModel = new Process();
        $questions = $assessmentModel->getAllQuestions(false);
        $processes = $processModel->getAll(true);
        $editQuestion = null;

        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $editQuestion = $assessmentModel->getQuestionById(intval($_GET['id']));
        }

        require_once 'app/views/admin/questions.php';
    }

    public static function saveQuestion() {
        self::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=questions');
            exit;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $processId = isset($_POST['process_id']) ? intval($_POST['process_id']) : 0;
        $question = trim($_POST['question'] ?? '');
        $practice = trim($_POST['practice_reference'] ?? '');
        $weight = isset($_POST['weight']) ? intval($_POST['weight']) : 1;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($processId <= 0 || $question === '') {
            header('Location: index.php?page=questions&error=missing_fields');
            exit;
        }

        $assessmentModel = new Assessment();
        if ($id > 0) {
            $assessmentModel->updateQuestion([
                'id' => $id,
                'process_id' => $processId,
                'question' => $question,
                'practice_reference' => $practice,
                'weight' => max(1, $weight),
                'is_active' => $isActive
            ]);
        } else {
            $assessmentModel->createQuestion([
                'process_id' => $processId,
                'question' => $question,
                'practice_reference' => $practice,
                'weight' => max(1, $weight),
                'is_active' => $isActive
            ]);
        }

        header('Location: index.php?page=questions&success=1');
        exit;
    }

    public static function deleteQuestion() {
        self::requireAdmin();

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=questions');
            exit;
        }

        $assessmentModel = new Assessment();
        $assessmentModel->deleteQuestion(intval($_GET['id']));

        header('Location: index.php?page=questions&deleted=1');
        exit;
    }

    public static function toggleQuestion() {
        self::requireAdmin();

        if (!isset($_GET['id']) || !isset($_GET['value'])) {
            header('Location: index.php?page=questions');
            exit;
        }

        $assessmentModel = new Assessment();
        $assessmentModel->toggleQuestionActive(intval($_GET['id']), intval($_GET['value']));

        header('Location: index.php?page=questions&success=1');
        exit;
    }
}
