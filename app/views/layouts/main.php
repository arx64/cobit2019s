<?php
/**
 * Main Layout Template
 * Layout utama aplikasi dengan sidebar
 */

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah halaman adalah login
$isLoginPage = !isset($_SESSION['user_id']);

// Ambil halaman aktif
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Base URL
$baseUrl = 'index.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Analisis Risiko TI berbasis COBIT 2019 untuk e-Raport">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Analisis Pengelolaan Layanan Desa Bogak Besar</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="public/assets/css/style.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?php if (isset($extraCSS)) echo $extraCSS; ?>
</head>
<body>
    <?php if (!$isLoginPage): ?>
    <!-- Sidebar Layout -->
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <!-- <div class="brand-logo">
                    <i class="bi bi-shield-check"></i>
                    <span>COBIT 2019</span>
                </div> -->
                <p class="text-white mb-0">Website Pengelolaan Layanan</p>
            </div>
            
            <ul class="list-unstyled components">
                <li class="nav-header">MENU</li>
                <li class="<?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                    <a href="<?php echo $baseUrl; ?>?page=dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-header">FRAMEWORK</li>
                <li class="<?php echo $currentPage === 'framework' ? 'active' : ''; ?>">
                    <a href="<?php echo $baseUrl; ?>?page=framework">
                        <i class="bi bi-diagram-3"></i>
                        <span>Framework COBIT</span>
                    </a>
                </li>
                <li class="<?php echo $currentPage === 'design-factor' ? 'active' : ''; ?>">
                    <a href="<?php echo $baseUrl; ?>?page=design-factor">
                        <i class="bi bi-sliders"></i>
                        <span>Design Factor</span>
                    </a>
                </li>
                
                <li class="nav-header">PENILAIAN</li>
                <li class="<?php echo $currentPage === 'data-penilaian' ? 'active' : ''; ?>">
                    <a href="<?php echo $baseUrl; ?>?page=data-penilaian">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Analisis</span>
                    </a>
                </li>
                <li class="<?php echo $currentPage === 'rekomendasi' ? 'active' : ''; ?>">
                    <a href="<?php echo $baseUrl; ?>?page=rekomendasi">
                        <i class="bi bi-lightbulb"></i>
                        <span>Rekomendasi</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <a href="<?php echo $baseUrl; ?>?page=logout" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    
                    <span class="navbar-brand ms-3 d-none d-md-block">
                        <?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?>
                    </span>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="user-info text-end me-3 d-none d-md-block">
                            <div class="fw-semibold"><?php echo $_SESSION['user_name'] ?? 'User'; ?></div>
                            <small class="text-muted text-uppercase"><?php echo $_SESSION['user_role'] ?? 'User'; ?></small>
                        </div>
                        <div class="user-avatar">
                            <div class="avatar-circle">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="main-content">
                <?php echo $content; ?>
            </main>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="text-center text-muted">
                    <small>&copy; <?php echo date('Y'); ?> Sistem Analisis Pengelolaan Layanan Desa Bogak Besar</small>
                </div>
            </footer>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Login Page Layout -->
    <?php echo $content; ?>
    <?php endif; ?>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="public/assets/js/main.js"></script>
    
    <?php if (isset($extraJS)) echo $extraJS; ?>
</body>
</html>
