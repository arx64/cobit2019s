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
    public function getQuestionsByProcess($processId) {
        $stmt = $this->db->prepare("SELECT * FROM assessment_questions WHERE process_id = :process_id ORDER BY id ASC");
        $stmt->execute(['process_id' => $processId]);
        return $stmt->fetchAll();
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

        if ($existing) {

            $stmt = $this->db->prepare("
            UPDATE assessment_answers
            SET value = ?, updated_at = NOW()
            WHERE id = ?
        ");

            return $stmt->execute([
                $data['value'],
                $existing['id']
            ]);
        } else {

            $stmt = $this->db->prepare("
            INSERT INTO assessment_answers
            (question_id, respondent_id, assessment_date, value, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");

            return $stmt->execute([
                $data['question_id'],
                $data['respondent_id'],
                $data['assessment_date'],
                $data['value']
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
     * Hitung capability level berdasarkan proses dan tanggal opsional
     */
    public function calculateCapabilityLevel($processId, $date = null) {
        $sql = "
            SELECT AVG(a.value) as avg_score 
            FROM assessment_answers a 
            JOIN assessment_questions q ON a.question_id = q.id 
            WHERE q.process_id = :process_id";
        
        $params = ['process_id' => $processId];
        if ($date) {
            $sql .= " AND DATE(a.updated_at) = :date";
            $params['date'] = $date;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['avg_score'] ? round($result['avg_score'], 2) : 0;
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
        $stmt = $this->db->query("SELECT DISTINCT DATE(updated_at) as date FROM assessment_answers ORDER BY date DESC");
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
     * Hapus semua jawaban untuk reset
     */
    public function resetAnswers() {
        return $this->db->query("DELETE FROM assessment_answers");
    }
}
