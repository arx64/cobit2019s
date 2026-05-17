<?php
/**
 * Respondent Controller
 * Mengelola CRUD responden kantor desa
 */

require_once 'app/models/Respondent.php';

class RespondentController {
    public static function index() {
        $respondentModel = new Respondent();
        $respondents = $respondentModel->getAll();

        $editRespondent = null;
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
            $editRespondent = $respondentModel->getById(intval($_GET['id']));
        }

        require_once 'app/views/respondent/index.php';
    }

    public static function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=respondents');
            exit;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $category = trim($_POST['category'] ?? 'operator_sistem');

        if ($name === '' || $position === '') {
            header('Location: index.php?page=respondents&error=1');
            exit;
        }

        $respondentModel = new Respondent();

        if ($id > 0) {
            $respondentModel->update([
                'id' => $id,
                'name' => $name,
                'position' => $position,
                'category' => $category
            ]);
        } else {
            $respondentModel->create([
                'name' => $name,
                'position' => $position,
                'category' => $category
            ]);
        }

        header('Location: index.php?page=respondents&success=1');
        exit;
    }

    public static function delete() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?page=respondents');
            exit;
        }

        $respondentModel = new Respondent();
        $respondentModel->delete(intval($_GET['id']));

        header('Location: index.php?page=respondents&deleted=1');
        exit;
    }
}
