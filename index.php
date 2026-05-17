<?php
/**
 * Sistem Analisis Risiko TI berbasis COBIT 2019
 * Front Controller - Router Utama
 * 
 * @author Sistem Analisis Risiko TI
 * @version 1.0
 */

// Start session
session_start();

// Set error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Define base path
define('BASE_PATH', __DIR__);

// Load configuration
require_once 'config/database.php';

// Load core
require_once 'core/App.php';

// Load controllers
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/FrameworkController.php';
require_once 'app/controllers/DesignFactorController.php';
require_once 'app/controllers/AssessmentController.php';
require_once 'app/controllers/RecommendationController.php';
require_once 'app/controllers/RespondentController.php';

// Route the request
App::route();
