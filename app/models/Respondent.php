<?php
/**
 * Respondent Model
 * Mengelola data responden Kantor Desa
 */

require_once 'config/database.php';

class Respondent {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM respondents ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM respondents WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO respondents (name, position, category) VALUES (:name, :position, :category)");
        return $stmt->execute([
            'name' => $data['name'],
            'position' => $data['position'],
            'category' => $data['category']
        ]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE respondents SET name = :name, position = :position, category = :category WHERE id = :id");
        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'position' => $data['position'],
            'category' => $data['category']
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM respondents WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
