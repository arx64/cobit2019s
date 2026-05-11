<?php
/**
 * 404 Error View
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .error-container {
            text-align: center;
            color: white;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            opacity: 0.9;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-container">
            <div class="error-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="error-code">404</div>
            <h2 class="mb-3">Halaman Tidak Ditemukan</h2>
            <p class="mb-4">Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.</p>
            <a href="index.php?page=dashboard" class="btn btn-light btn-lg">
                <i class="bi bi-house-door me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
