<?php
/**
 * Process Model
 * Mengelola data proses COBIT
 */

require_once 'config/database.php';

class Process {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Ambil semua proses
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM processes ORDER BY code ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Ambil proses berdasarkan ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM processes WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Ambil proses berdasarkan kode
     */
    public function getByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM processes WHERE code = :code LIMIT 1");
        $stmt->execute(['code' => $code]);
        return $stmt->fetch();
    }
    
    /**
     * Hitung total proses
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM processes");
        return $stmt->fetch()['total'];
    }
}
