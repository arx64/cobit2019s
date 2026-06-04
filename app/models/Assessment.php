<?php
/**
 * Assessment Model
 * Mengelola data penilaian dan hasil assessment
 */

require_once 'config/database.php';

class Assessment {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Ambil semua pertanyaan berdasarkan proses
     */
    public function getQuestionsByProcess($processId, $activeOnly = true) {
        $sql = "SELECT * FROM assessment_questions WHERE process_id = :process_id";
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        $sql .= " ORDER BY id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['process_id' => $processId]);
        return $stmt->fetchAll();
    }

    public function getAllQuestions($activeOnly = true) {
        $sql = "SELECT q.*, p.code AS process_code, p.name AS process_name FROM assessment_questions q JOIN processes p ON q.process_id = p.id";
        if ($activeOnly) {
            $sql .= " WHERE q.is_active = 1";
        }
        $sql .= " ORDER BY p.code ASC, q.id ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getQuestionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM assessment_questions WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createQuestion($data) {
        $stmt = $this->db->prepare("INSERT INTO assessment_questions (process_id, question, practice_reference, weight, is_active, created_at) VALUES (:process_id, :question, :practice_reference, :weight, :is_active, NOW())");
        return $stmt->execute([
            'process_id' => $data['process_id'],
            'question' => $data['question'],
            'practice_reference' => $data['practice_reference'],
            'weight' => isset($data['weight']) ? intval($data['weight']) : 1,
            'is_active' => isset($data['is_active']) ? intval($data['is_active']) : 1
        ]);
    }

    public function updateQuestion($data) {
        $stmt = $this->db->prepare("UPDATE assessment_questions SET process_id = :process_id, question = :question, practice_reference = :practice_reference, weight = :weight, is_active = :is_active WHERE id = :id");
        return $stmt->execute([
            'id' => $data['id'],
            'process_id' => $data['process_id'],
            'question' => $data['question'],
            'practice_reference' => $data['practice_reference'],
            'weight' => isset($data['weight']) ? intval($data['weight']) : 1,
            'is_active' => isset($data['is_active']) ? intval($data['is_active']) : 1
        ]);
    }

    public function deleteQuestion($id) {
        $stmt = $this->db->prepare("DELETE FROM assessment_questions WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleQuestionActive($id, $active) {
        $stmt = $this->db->prepare("UPDATE assessment_questions SET is_active = :is_active WHERE id = :id");
        return $stmt->execute(['id' => $id, 'is_active' => $active ? 1 : 0]);
    }
    
    /**
     * Simpan jawaban penilaian
     */
    // public function saveAnswer($data) {
    //     // Cek apakah sudah ada jawaban untuk pertanyaan dan responden tersebut
    //     $check = $this->db->prepare("SELECT id FROM assessment_answers WHERE question_id = :question_id AND respondent_id = :respondent_id LIMIT 1");
    //     $check->execute([
    //         'question_id' => $data['question_id'],
    //         'respondent_id' => $data['respondent_id']
    //     ]);

    //     if ($check->fetch()) {
    //         // Update jawaban yang sudah ada
    //         $stmt = $this->db->prepare("UPDATE assessment_answers SET value = :value WHERE question_id = :question_id AND respondent_id = :respondent_id");
    //     } else {
    //         // Insert jawaban baru
    //         $stmt = $this->db->prepare("INSERT INTO assessment_answers (question_id, respondent_id, value) VALUES (:question_id, :respondent_id, :value)");
    //     }

    //     return $stmt->execute([
    //         'question_id' => $data['question_id'],
    //         'respondent_id' => $data['respondent_id'],
    //         'value' => $data['value']
    //     ]);
    // }

    public function saveAnswer($data)
    {
        // cek existing
        $check = $this->db->prepare("
        SELECT id
        FROM assessment_answers
        WHERE question_id = ?
        AND respondent_id = ?
        AND assessment_date = ?
    ");

        $check->execute([
            $data['question_id'],
            $data['respondent_id'],
            $data['assessment_date']
        ]);

        $existing = $check->fetch();

        $answeredBy = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

        if ($existing) {
            $stmt = $this->db->prepare("
            UPDATE assessment_answers
            SET value = ?, updated_at = NOW(), answered_by = ?
            WHERE id = ?
        ");

            return $stmt->execute([
                $data['value'],
                $answeredBy,
                $existing['id']
            ]);
        } else {
            $stmt = $this->db->prepare("
            INSERT INTO assessment_answers
            (question_id, respondent_id, assessment_date, value, answered_by, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");

            return $stmt->execute([
                $data['question_id'],
                $data['respondent_id'],
                $data['assessment_date'],
                $data['value'],
                $answeredBy
            ]);
        }
    }
    
    /**
     * Ambil jawaban berdasarkan pertanyaan, responden, dan tanggal opsional
     */
    // public function getAnswerByQuestion($questionId, $respondentId = null, $date = null) {
    //     if ($respondentId !== null) {
    //         if ($date) {
    //             $stmt = $this->db->prepare("SELECT * FROM assessment_answers WHERE question_id = :question_id AND respondent_id = :respondent_id AND DATE(updated_at) = :date LIMIT 1");
    //             $stmt->execute([
    //                 'question_id' => $questionId,
    //                 'respondent_id' => $respondentId,
    //                 'date' => $date
    //             ]);
    //             return $stmt->fetch();
    //         }

    //         $stmt = $this->db->prepare("SELECT * FROM assessment_answers WHERE question_id = :question_id AND respondent_id = :respondent_id LIMIT 1");
    //         $stmt->execute([
    //             'question_id' => $questionId,
    //             'respondent_id' => $respondentId
    //         ]);
    //         return $stmt->fetch();
    //     }

    //     $stmt = $this->db->prepare("SELECT * FROM assessment_answers WHERE question_id = :question_id LIMIT 1");
    //     $stmt->execute(['question_id' => $questionId]);
    //     return $stmt->fetch();
    // }
    public function getAnswerByQuestion($questionId, $respondentId, $date)
    {
        $stmt = $this->db->prepare("
        SELECT *
        FROM assessment_answers
        WHERE question_id = ?
        AND respondent_id = ?
        AND assessment_date = ?
        LIMIT 1
    ");

        $stmt->execute([
            $questionId,
            $respondentId,
            $date
        ]);

        return $stmt->fetch();
    }

    
    /**
     * Hitung capability level berdasarkan proses, responden, dan tanggal opsional
     * Menggunakan metode COBIT: rata-rata per sub-practice, lalu rata-rata sub-practice
     */
    public function calculateCapabilityLevel($processId, $respondentId = null, $date = null)
    {
        $sql = "
        SELECT 
            SUM(a.value) as total_score,
            COUNT(a.id) as total_answers
        FROM assessment_answers a
        JOIN assessment_questions q ON a.question_id = q.id
        WHERE q.process_id = :process_id
    ";

        $params = [
            'process_id' => $processId
        ];

        if ($respondentId !== null) {
            $sql .= " AND a.respondent_id = :respondent_id";
            $params['respondent_id'] = $respondentId;
        }

        if ($date) {
            $sql .= " AND a.assessment_date = :date";
            $params['date'] = $date;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch();

        if (!$result || $result['total_answers'] == 0) {
            return 0;
        }

        $capability = $result['total_score'] / $result['total_answers'];

        // For DSS01 (process id 1) return full precision (no rounding)
        if ($processId === 1) {
            // return $capability;
        }

        return round($capability, 2);
    }
    
    /**
     * Ambil tanggal-tanggal penilaian untuk responden
     */
    // public function getAssessmentDatesByRespondent($respondentId) {
    //     $stmt = $this->db->prepare("SELECT DISTINCT DATE(updated_at) as date FROM assessment_answers WHERE respondent_id = :respondent_id ORDER BY date DESC");
    //     $stmt->execute(['respondent_id' => $respondentId]);
    //     return $stmt->fetchAll();
    // }
    public function getAssessmentDatesByRespondent($respondentId)
    {
        $stmt = $this->db->prepare("
        SELECT DISTINCT assessment_date as date
        FROM assessment_answers
        WHERE respondent_id = ?
        ORDER BY assessment_date DESC
    ");

        $stmt->execute([$respondentId]);

        return $stmt->fetchAll();
    }

    /**
     * Ambil semua tanggal penilaian
     */
    public function getAssessmentDates() {
        $stmt = $this->db->query("SELECT DISTINCT assessment_date as date FROM assessment_answers ORDER BY assessment_date DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Hitung total penilaian yang sudah dilakukan
     */
    public function countAssessments() {
        $stmt = $this->db->query("SELECT COUNT(DISTINCT question_id) as total FROM assessment_answers");
        return $stmt->fetch()['total'];
    }

    /**
     * Hitung total penilaian untuk tanggal tertentu
     */
    public function countAssessmentsByDate($date) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM assessment_answers WHERE assessment_date = :date");
        $stmt->execute(['date' => $date]);
        return $stmt->fetch()['total'] ?? 0;
    }
    
    /**
     * Hapus semua jawaban untuk reset
     */
    public function resetAnswers() {
        return $this->db->query("DELETE FROM assessment_answers");
    }
}
