<?php
/**
 * User Model
 * Mengelola data pengguna
 */

require_once 'config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Mencari user berdasarkan email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Mencari user berdasarkan ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT id, name, email, role FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Membuat user baru
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user'
        ]);
    }
    
    /**
     * Verifikasi password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Update password
     */
    public function updatePassword($id, $newPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }
    
    /**
     * Hitung total user
     */
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        return $stmt->fetch()['total'];
    }
}
