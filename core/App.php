<?php
/**
 * Core Application Class
 * Sistem Analisis Risiko TI berbasis COBIT 2019
 */

class App {
    
    /**
     * Routing aplikasi berdasarkan parameter page
     */
    public static function route() {
        $page = isset($_GET['page']) ? $_GET['page'] : 'login';

        // Daftar halaman yang memerlukan autentikasi
        $protectedPages = [
            'dashboard',
            'framework',
            'proses',
            'design-factor',
            'data-penilaian',

            'rekomendasi',
            'rekomendasi-dss01',
            'rekomendasi-dss02',

            'respondents',
            'save-respondent',
            'delete-respondent',
            'processes',
            'save-process',
            'delete-process',
            'toggle-process',
            'questions',
            'save-question',
            'delete-question',
            'toggle-question',

            'logout',
            'simpan-penilaian',
            'get-questions'
        ];
        
        // Cek autentikasi untuk halaman yang dilindungi
        if (in_array($page, $protectedPages) && !isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Redirect ke dashboard jika sudah login dan mengakses login page
        if ($page === 'login' && isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        
        // Routing berdasarkan page
        switch ($page) {
            // Auth Routes
            case 'login':
                AuthController::index();
                break;
            case 'auth-login':
                AuthController::login();
                break;
            case 'logout':
                AuthController::logout();
                break;
                
            // Dashboard
            case 'dashboard':
                DashboardController::index();
                break;
                
            // Framework COBIT
            case 'framework':
                FrameworkController::index();
                break;
            case 'proses':
                FrameworkController::proses();
                break;
                
            // Respondent
            case 'respondents':
                RespondentController::index();
                break;
            case 'save-respondent':
                RespondentController::save();
                break;
            case 'delete-respondent':
                RespondentController::delete();
                break;

            // Management
            case 'processes':
                ManagementController::processes();
                break;
            case 'save-process':
                ManagementController::saveProcess();
                break;
            case 'delete-process':
                ManagementController::deleteProcess();
                break;
            case 'toggle-process':
                ManagementController::toggleProcess();
                break;
            case 'questions':
                ManagementController::questions();
                break;
            case 'save-question':
                ManagementController::saveQuestion();
                break;
            case 'delete-question':
                ManagementController::deleteQuestion();
                break;
            case 'toggle-question':
                ManagementController::toggleQuestion();
                break;

            // Design Factor
            case 'design-factor':
                DesignFactorController::index();
                break;
                
            // Data Penilaian
            case 'data-penilaian':
                AssessmentController::index();
                break;
            case 'simpan-penilaian':
                AssessmentController::save();
                break;
            case 'get-questions':
                AssessmentController::getQuestions();
                break;

            // Rekomendasi
            case 'rekomendasi':
                RecommendationController::index();
                break;

            case 'rekomendasi-dss01':
                RecommendationController::dss01();
                break;

            case 'rekomendasi-dss02':
                RecommendationController::dss02();
                break;
                
            // Default - 404
            default:
                self::show404();
                break;
        }
    }
    
    /**
     * Tampilkan halaman 404
     */
    private static function show404() {
        http_response_code(404);
        require_once 'app/views/errors/404.php';
    }
    
    /**
     * Helper untuk redirect
     */
    public static function redirect($page) {
        header("Location: index.php?page={$page}");
        exit;
    }
    
    /**
     * Helper untuk mendapatkan base URL
     */
    public static function baseUrl() {
        return dirname($_SERVER['SCRIPT_NAME']);
    }
    
    /**
     * Helper untuk sanitasi input
     */
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}
