<?php
/**
 * Design Factor Model
 * Mengelola data faktor desain COBIT
 */

require_once 'config/database.php';

class DesignFactor {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Ambil semua design factors
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM design_factors ORDER BY code ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Ambil design factor berdasarkan ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM design_factors WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Ambil design factor berdasarkan kode
     */
    public function getByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM design_factors WHERE code = :code LIMIT 1");
        $stmt->execute(['code' => $code]);
        return $stmt->fetch();
    }
    
    /**
     * Hitung total design factors
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM design_factors");
        return $stmt->fetch()['total'];
    }
}
