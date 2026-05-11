<?php
/**
 * Konfigurasi Database
 * Sistem Analisis Risiko TI berbasis COBIT 2019
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cobit2019_bogakbesar');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Class Database
 * Mengelola koneksi database menggunakan PDO
 */
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    /**
     * Singleton pattern untuk koneksi database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Mendapatkan objek PDO connection
     */
    public function getConnection() {
        return $this->connection;
    }
}

/**
 * Helper function untuk mendapatkan koneksi database
 */
function getDB() {
    return Database::getInstance()->getConnection();
}
