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
    public function saveAnswer($data) {
        // Cek apakah sudah ada jawaban
        $check = $this->db->prepare("SELECT id FROM assessment_answers WHERE question_id = :question_id LIMIT 1");
        $check->execute(['question_id' => $data['question_id']]);
        
        if ($check->fetch()) {
            // Update jawaban yang sudah ada
            $stmt = $this->db->prepare("UPDATE assessment_answers SET value = :value WHERE question_id = :question_id");
        } else {
            // Insert jawaban baru
            $stmt = $this->db->prepare("INSERT INTO assessment_answers (question_id, value) VALUES (:question_id, :value)");
        }
        
        return $stmt->execute([
            'question_id' => $data['question_id'],
            'value' => $data['value']
        ]);
    }
    
    /**
     * Ambil jawaban berdasarkan pertanyaan
     */
    public function getAnswerByQuestion($questionId) {
        $stmt = $this->db->prepare("SELECT * FROM assessment_answers WHERE question_id = :question_id LIMIT 1");
        $stmt->execute(['question_id' => $questionId]);
        return $stmt->fetch();
    }
    
    /**
     * Hitung capability level berdasarkan proses
     */
    public function calculateCapabilityLevel($processId) {
        $stmt = $this->db->prepare("
            SELECT AVG(a.value) as avg_score 
            FROM assessment_answers a 
            JOIN assessment_questions q ON a.question_id = q.id 
            WHERE q.process_id = :process_id
        ");
        $stmt->execute(['process_id' => $processId]);
        $result = $stmt->fetch();
        
        return $result['avg_score'] ? round($result['avg_score'], 2) : 0;
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
