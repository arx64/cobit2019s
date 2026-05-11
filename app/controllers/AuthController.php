<?php
/**
 * Auth Controller
 * Mengelola autentikasi pengguna
 */

require_once 'app/models/User.php';

class AuthController {
    
    /**
     * Tampilkan halaman login
     */
    public static function index() {
        $error = '';
        require_once 'app/views/auth/login.php';
    }
    
    /**
     * Proses login
     */
    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Validasi input
        if (empty($email) || empty($password)) {
            $error = 'Email dan password wajib diisi!';
            require_once 'app/views/auth/login.php';
            return;
        }
        
        // Cek user
        $userModel = new User();
        $user = $userModel->findByEmail($email);
        
        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            $error = 'Email atau password salah!';
            require_once 'app/views/auth/login.php';
            return;
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirect ke dashboard
        header('Location: index.php?page=dashboard');
        exit;
    }
    
    /**
     * Proses logout
     */
    public static function logout() {
        // Hapus semua session
        session_unset();
        session_destroy();
        
        // Redirect ke login
        header('Location: index.php?page=login');
        exit;
    }
}
