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
    public function getAll($activeOnly = false) {
        if ($activeOnly) {
            $stmt = $this->db->prepare("SELECT * FROM processes WHERE is_active = 1 ORDER BY code ASC");
            $stmt->execute();
        } else {
            $stmt = $this->db->query("SELECT * FROM processes ORDER BY code ASC");
        }
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
     * Buat proses baru
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO processes (code, name, description, is_active, created_at) VALUES (:code, :name, :description, :is_active, NOW())");
        return $stmt->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => isset($data['is_active']) ? intval($data['is_active']) : 1
        ]);
    }
    
    /**
     * Update proses
     */
    public function update($data) {
        $stmt = $this->db->prepare("UPDATE processes SET code = :code, name = :name, description = :description, is_active = :is_active WHERE id = :id");
        return $stmt->execute([
            'id' => $data['id'],
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => isset($data['is_active']) ? intval($data['is_active']) : 1
        ]);
    }

    /**
     * Hapus proses
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM processes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Ubah status aktif proses
     */
    public function toggleActive($id, $active) {
        $stmt = $this->db->prepare("UPDATE processes SET is_active = :is_active WHERE id = :id");
        return $stmt->execute(['id' => $id, 'is_active' => $active ? 1 : 0]);
    }
    
    /**
     * Hitung total proses
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM processes");
        return $stmt->fetch()['total'];
    }
}
