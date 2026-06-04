<?php

/**
 * Recommendation View
 */

$title = 'Rekomendasi';
$pageTitle = 'Hasil Analisis & Rekomendasi';

// Prepare data for chart
$chartLabels = [];
$chartCurrent = [];
$chartTarget = [];
$chartGaps = [];

foreach ($results as $result) {
    $chartLabels[] = $result['process']['code'];
    $chartCurrent[] = $result['capability_level'];
    $chartTarget[] = $targetLevel;
    $chartGaps[] = $result['recommendation']['gap'];
}

$currentResult = $results[0] ?? null;

$currentProcessCode = $currentResult['process']['code'] ?? '-';
$currentProcessName = $currentResult['process']['name'] ?? '-';

$currentCapability = $currentResult['capability_level'] ?? 0;

$currentGap = $currentResult['recommendation']['gap'] ?? 0;

$currentLevel = $currentResult['recommendation']['level'] ?? '-';

$reportDate = isset($_GET['date']) && !empty($_GET['date'])
    ? $_GET['date']
    : date('Y-m-d');

function formatDateIndo($dateString, $includeTime = false) {
    $timestamp = strtotime($dateString);
    if ($timestamp === false) {
        return $dateString;
    }

    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $day = date('d', $timestamp);
    $month = $months[(int) date('n', $timestamp)];
    $year = date('Y', $timestamp);
    $formatted = sprintf('%s %s %s', $day, $month, $year);

    if ($includeTime) {
        $formatted .= ' ' . date('H:i:s', $timestamp);
    }

    return $formatted;
}

ob_start();
?>

<!-- Print Header (only visible when printing) -->
<div class="print-header d-none">
    <h2>Laporan Hasil Analisis COBIT 2019</h2>
    <p>Sistem Analisis Pengelolaan Layanan Desa Bogak Besar berbasis COBIT 2019</p>
    <p>Tanggal: <?php echo formatDateIndo(date('Y-m-d')); ?></p>
    <hr>
</div>

<!-- Page Header with Actions -->
<div class="row mb-4 no-print">
    <div class="col-12">
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4><i class="bi bi-lightbulb me-2"></i>Hasil Analisis & Rekomendasi</h4>
                <p class="text-muted mb-0">Capability level, gap analysis, dan rekomendasi perbaikan</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="toggleCharts()">
                    <i class="bi bi-bar-chart-line me-2"></i><span id="chartToggleText">Sembunyikan Grafik</span>
                </button>
                <button class="btn btn-primary" onclick="printReport()">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter Section -->
<div class="row mb-4 no-print">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label for="dateFilter" class="form-label mb-0"><i class="bi bi-calendar-event me-2"></i>Filter Tanggal</label>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <select id="dateFilter" class="form-select" onchange="filterByDate()">
                                <option value="">-- Pilih Tanggal --</option>
                                <?php 
                                if (!empty($availableDates)):
                                    foreach ($availableDates as $dateRow):
                                        $date = $dateRow['date'];
                                        $selected = ($date === $reportDate) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $date; ?>" <?php echo $selected; ?>>
                                        <?php echo formatDateIndo($date); ?>
                                    </option>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </select>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-secondary" title="Reset">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card summary-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="summary-icon">
                        <i class="bi bi-bar-chart-line fs-1"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo number_format($capabilityLevel, 2); ?></h3>
                        <p class="mb-0 opacity-75">Capability Level</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card summary-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="summary-icon">
                        <i class="bi bi-exclamation-triangle fs-1"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo number_format($totalGaps, 2); ?></h3>
                        <p class="mb-0 opacity-75">Total Gap</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card summary-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="summary-icon">
                        <i class="bi bi-bullseye fs-1"></i>
                    </div>
                    <div class="ms-3">
                        <h3 class="mb-0"><?php echo $targetLevel; ?>.0</h3>
                        <p class="mb-0 opacity-75">Target Level</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($results)): ?>

    <!-- Charts Section -->
    <div id="chartsSection" class="row mb-4 no-print">

        <?php if ($selectedProcessId == 1): ?>

            <!-- Grafik DSS01 -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Grafik Capability Level DSS01
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="capabilityChartDSS01"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($selectedProcessId == 2): ?>

            <!-- Grafik DSS02 -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Grafik Capability Level DSS02
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="capabilityChartDSS02"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <!-- Semua grafik -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Grafik Capability Level DSS01
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="capabilityChartDSS01"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>
                            Grafik Capability Level DSS02
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="capabilityChartDSS02"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <!-- Priority Process -->
    <?php if ($priorityProcess && $priorityProcess['recommendation']['gap'] > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-octagon me-2"></i>Prioritas Perbaikan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><?php echo $priorityProcess['process']['code']; ?> - <?php echo $priorityProcess['process']['name']; ?></h5>
                                <p class="text-muted">Proses ini memiliki gap terbesar dan memerlukan perhatian segera.</p>
                                <div class="d-flex gap-3">
                                    <div>
                                        <span class="text-muted small">Current Level</span>
                                        <div class="h4 mb-0"><?php echo number_format($priorityProcess['capability_level'], 2); ?></div>
                                    </div>
                                    <div>
                                        <span class="text-muted small">Target</span>
                                        <div class="h4 mb-0"><?php echo $targetLevel; ?>.0</div>
                                    </div>
                                    <div>
                                        <span class="text-muted small">Gap</span>
                                        <div class="h4 mb-0 text-danger"><?php echo $priorityProcess['recommendation']['gap']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="bi bi-lightbulb me-2"></i>Rekomendasi Prioritas:</h6>
                                <ol class="mb-0">
                                    <?php foreach (array_slice($priorityProcess['recommendation']['recommendations'], 0, 3) as $rec): ?>
                                        <?php echo $rec; ?>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Results Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Ringkasan Hasil Penilaian</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="resultsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Proses</th>
                                    <th>Capability Level</th>
                                    <th>Target</th>
                                    <th>Gap</th>
                                    <th>Status</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $result['process']['code']; ?></strong>
                                            <div class="small text-muted"><?php echo $result['process']['name']; ?></div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 10px; width: 100px;">
                                                    <div class="progress-bar bg-<?php echo $result['recommendation']['color']; ?>"
                                                        style="width: <?php echo ($result['capability_level'] / 5) * 100; ?>%">
                                                    </div>
                                                </div>
                                                <span class="ms-2 fw-semibold"><?php echo number_format($result['capability_level'], 2); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $targetLevel; ?>.0</td>
                                        <td>
                                            <?php if ($result['recommendation']['gap'] > 0): ?>
                                                <span class="badge bg-danger"><?php echo $result['recommendation']['gap']; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="bi bi-check-lg"></i></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $result['recommendation']['color']; ?>">
                                                <?php echo $result['recommendation']['level']; ?>
                                            </span>
                                        </td>
                                        <td class="no-print">
                                            <a href="#detail-<?php echo $result['process']['id']; ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Recommendations -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-journal-text me-2"></i>Detail Rekomendasi per Proses</h5>
        </div>
        <?php foreach ($results as $result): ?>
            <div class="col-lg-6 mb-4" id="detail-<?php echo $result['process']['id']; ?>">
                <div class="card recommendation-detail h-100 border-<?php echo $result['recommendation']['color']; ?>">
                    <div class="card-header bg-<?php echo $result['recommendation']['color']; ?> text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo $result['process']['code']; ?> - <?php echo $result['process']['name']; ?></h5>
                            <span class="badge bg-white text-<?php echo $result['recommendation']['color']; ?>">
                                Level <?php echo number_format($result['capability_level'], 2); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4 text-center">
                                <div class="small text-muted">Current</div>
                                <div class="h5 mb-0"><?php echo number_format($result['capability_level'], 2); ?></div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="small text-muted">Target</div>
                                <div class="h5 mb-0"><?php echo $targetLevel; ?>.0</div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="small text-muted">Gap</div>
                                <div class="h5 mb-0 text-<?php echo $result['recommendation']['gap'] > 0 ? 'danger' : 'success'; ?>">
                                    <?php echo $result['recommendation']['gap']; ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">
                            <i class="bi bi-check2-square me-2"></i>Rekomendasi Perbaikan:
                        </h6>
                        <ul class="recommendation-list">
                            <?php foreach ($result['recommendation']['recommendations'] as $rec): ?>
                                <li><?php echo $rec; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-footer bg-light no-print">
                        <a href="index.php?page=data-penilaian&process=<?php echo $result['process']['id']; ?>"
                            class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-pencil me-1"></i> Update Penilaian
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Print Footer -->
    <div class="print-footer d-none">
        <hr>
        <p class="text-center text-muted">
            Dicetak dari Sistem Analisis Risiko TI berbasis COBIT 2019<br>
            Tanggal: <?php echo formatDateIndo(date('Y-m-d H:i:s'), true); ?>
        </p>
    </div>

<?php else: ?>
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-data display-1 text-muted"></i>
                    <h4 class="mt-4 text-muted">Belum Ada Data Penilaian</h4>
                    <p class="text-muted">Silakan lakukan penilaian terlebih dahulu untuk melihat hasil analisis dan rekomendasi.</p>
                    <a href="index.php?page=data-penilaian" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-plus-lg me-2"></i>Mulai Penilaian
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .summary-card .card-body {
        padding: 1.5rem;
    }

    .summary-icon {
        opacity: 0.8;
    }

    .recommendation-detail {
        transition: transform 0.2s ease;
    }

    .recommendation-detail:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .recommendation-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recommendation-list li {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .recommendation-list li::before {
        content: '\F26B';
        font-family: 'bootstrap-icons';
        position: absolute;
        left: 0;
        color: #0d6efd;
    }

    /* Print Styles */
    @media print {

        .no-print,
        .sidebar,
        .navbar,
        #sidebarCollapse,
        .footer,
        .btn {
            display: none !important;
        }

        .print-header,
        .print-footer {
            display: block !important;
        }

        #content {
            width: 100% !important;
            margin-left: 0 !important;
        }

        .main-content {
            padding: 0 !important;
        }

        .card {
            break-inside: avoid;
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }

        .card-header {
            background: #f8f9fa !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table {
            font-size: 12pt;
        }

        .progress {
            background-color: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .progress-bar {
            background-color: #0d6efd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-primary {
            background-color: #0d6efd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-success {
            background-color: #198754 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-size: 12pt;
            line-height: 1.5;
        }

        h4,
        h5,
        h6 {
            page-break-after: avoid;
        }

        .recommendation-detail {
            break-inside: avoid;
            margin-bottom: 1rem;
        }
    }

    .print-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .print-header h2 {
        font-size: 24pt;
        margin-bottom: 0.5rem;
    }

    .print-header p {
        font-size: 12pt;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .print-footer {
        margin-top: 2rem;
        font-size: 10pt;
    }
</style>

<script>
    // Tree data from PHP
    const chartLabels = <?php echo json_encode($chartLabels); ?>;
    const chartCurrent = <?php echo json_encode($chartCurrent); ?>;
    const chartTarget = <?php echo json_encode($chartTarget); ?>;
    const chartGaps = <?php echo json_encode($chartGaps); ?>;

    // Data DSS01
    const dss01Labels = ['Current Level', 'Target Level'];
    const dss01Current = chartCurrent[0] ?? 0;
    const dss01Target = chartTarget[0] ?? 4;

    // Data DSS02
    const dss02Labels = ['Current Level', 'Target Level'];
    const dss02Current = chartCurrent[0] ?? 0;
    const dss02Target = chartTarget[0] ?? 4;

    // Common Options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 5,
                ticks: {
                    stepSize: 1
                },
                title: {
                    display: true,
                    text: 'Capability Level'
                }
            }
        }
    };

    // Grafik DSS01
    const dss01Canvas = document.getElementById('capabilityChartDSS01');

    if (dss01Canvas) {

        const ctxDSS01 = dss01Canvas.getContext('2d');

        new Chart(ctxDSS01, {
            type: 'bar',
            data: {
                labels: dss01Labels,
                datasets: [{
                    label: 'DSS01 – Manage Operations',
                    data: [dss01Current, dss01Target],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(25, 135, 84, 0.7)'
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

    }

    // Grafik DSS02
    const dss02Canvas = document.getElementById('capabilityChartDSS02');

    if (dss02Canvas) {

        const ctxDSS02 = dss02Canvas.getContext('2d');

        new Chart(ctxDSS02, {
            type: 'bar',
            data: {
                labels: dss02Labels,
                datasets: [{
                    label: 'DSS02 – Manage Service Requests and Incidents',
                    data: [dss02Current, dss02Target],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(25, 135, 84, 0.7)'
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

    }

    // Print Function
    function printReport() {

        const printWindow = window.open('', '_blank');

        const reportContent = `
    <html>
    <head>
        <title>Laporan COBIT 2019</title>

        <style>

            body{
                font-family: Arial, sans-serif;
                padding:40px;
                color:#000;
                line-height:1.6;
            }

            .header{
                display:flex;
                align-items:center;
                border-bottom:3px solid #000;
                padding-bottom:15px;
                margin-bottom:30px;
            }

            .logo{
                width:90px;
                height:90px;
                margin-right:20px;
            }

            .header-text{
                flex:1;
                text-align:center;
            }

            .header-text h2{
                margin:0;
                font-size:22px;
                text-transform:uppercase;
            }

            .header-text h3{
                margin:5px 0;
                font-size:18px;
            }

            .header-text p{
                margin:0;
                font-size:14px;
            }

            .report-title{
                text-align:center;
                margin:30px 0;
            }

            .report-title h3{
                margin:0;
                text-decoration:underline;
            }

            .info-table{
                width:100%;
                margin-bottom:25px;
            }

            .info-table td{
                padding:6px 0;
                vertical-align:top;
            }

            .section-title{
                margin-top:30px;
                margin-bottom:15px;
                font-size:18px;
                font-weight:bold;
            }

            table{
                width:100%;
                border-collapse:collapse;
                margin-top:10px;
            }

            table th,
            table td{
                border:1px solid #000;
                padding:10px;
                text-align:left;
            }

            table th{
                background:#f0f0f0;
            }

            .recommendation-box{
                margin-top:20px;
            }

            .recommendation-box ol{
                padding-left:20px;
            }

            .signature{
                width:300px;
                margin-left:auto;
                margin-top:60px;
                text-align:center;
            }

            .signature-space{
                height:80px;
            }

            @media print{
                body{
                    padding:0;
                }
            }

        </style>
    </head>

    <body>

        <div class="header">

            <img 
                src="public/assets/img/Lambang_Kabupaten_Serdang_Bedagai.png"
                class="logo"
            >

            <div class="header-text">
                <h2>Pemerintah Desa Bogak Besar</h2>
                <h3>Sistem Analisis Pengelolaan Layanan</h3>
                <p>Framework COBIT 2019</p>
            </div>

        </div>

        <div class="report-title">
            <h3>LAPORAN HASIL ANALISIS DAN REKOMENDASI</h3>
            <p>${new Date().toLocaleDateString('id-ID')}</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="220"><strong>Nama Responden</strong></td>
                <td>: <?php echo $respondentName; ?></td>
            </tr>

            <tr>
                <td><strong>Domain COBIT</strong></td>
                <td>: <?php echo $currentProcessCode; ?></td>
            </tr>

            <tr>
                <td><strong>Nama Proses</strong></td>
                <td>: <?php echo $currentProcessName; ?></td>
            </tr>

            <tr>
                <td><strong>Tanggal Penilaian</strong></td>
                <td>: <?php echo formatDateIndo($reportDate); ?></td>
            </tr>
        </table>

        <div class="section-title">
            Hasil Capability Level
        </div>

        <table>
            <thead>
                <tr>
                    <th>Capability Level</th>
                    <th>Target</th>
                    <th>Gap</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><?php echo number_format($currentCapability, 2); ?></td>
                    <td><?php echo $targetLevel; ?>.0</td>
                    <td><?php echo $currentGap; ?></td>
                    <td><?php echo $currentLevel; ?></td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">
            Rekomendasi Perbaikan
        </div>

        <div class="recommendation-box">
            <ol>

                <?php foreach ($currentResult['recommendation']['recommendations'] as $rec): ?>

                    <?php echo addslashes($rec); ?>

                <?php endforeach; ?>

            </ol>
        </div>

        <div class="signature">

            <p>
                Bogak Besar,
                <?php echo formatDateIndo($reportDate); ?>
            </p>

            <p>
                Mengetahui,
            </p>

            <div class="signature-space"></div>

            <strong>
                Kepala Desa Bogak Besar
            </strong>

        </div>

    </body>
    </html>
    `;

        printWindow.document.write(reportContent);

        printWindow.document.close();

        printWindow.focus();

        setTimeout(() => {
            printWindow.print();
        }, 500);
    }

    // Toggle Charts
    function toggleCharts() {
        const chartsSection = document.getElementById('chartsSection');
        const toggleText = document.getElementById('chartToggleText');

        if (chartsSection.classList.contains('d-none')) {
            chartsSection.classList.remove('d-none');
            toggleText.textContent = 'Sembunyikan Grafik';
        } else {
            chartsSection.classList.add('d-none');
            toggleText.textContent = 'Tampilkan Grafik';
        }
    }

    // Filter by Date
    function filterByDate() {
        const dateSelect = document.getElementById('dateFilter');
        const selectedDate = dateSelect.value;

        if (selectedDate) {
            // Build URL with date parameter
            const url = new URL(window.location);
            url.searchParams.set('date', selectedDate);
            window.location.href = url.toString();
        }
    }
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
