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
    public function getAll($activeOnly = false) {
        $sql = "SELECT * FROM design_factors";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }

        // fetch rows and apply natural numeric sort on the code (e.g. DF1, DF2, DF10)
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        usort($rows, function($a, $b) {
            // extract numeric part from code, fallback to 0
            $na = intval(preg_replace('/\D/', '', $a['code']));
            $nb = intval(preg_replace('/\D/', '', $b['code']));
            if ($na === $nb) {
                return strcmp($a['code'], $b['code']);
            }
            return $na <=> $nb;
        });

        return $rows;
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

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO design_factors (code, name, description, is_active, created_at) VALUES (:code, :name, :description, :is_active, NOW())");
        return $stmt->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => isset($data['is_active']) ? intval($data['is_active']) : 1
        ]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE design_factors SET code = :code, name = :name, description = :description, is_active = :is_active WHERE id = :id");
        return $stmt->execute([
            'id' => $data['id'],
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'is_active' => isset($data['is_active']) ? intval($data['is_active']) : 1
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM design_factors WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleActive($id, $active) {
        $stmt = $this->db->prepare("UPDATE design_factors SET is_active = :is_active WHERE id = :id");
        return $stmt->execute(['id' => $id, 'is_active' => $active ? 1 : 0]);
    }
    
    /**
     * Hitung total design factors
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM design_factors");
        return $stmt->fetch()['total'];
    }
}
