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

ob_start();
?>

<!-- Print Header (only visible when printing) -->
<div class="print-header d-none">
    <h2>Laporan Hasil Analisis COBIT 2019</h2>
    <p>Sistem Analisis Risiko TI untuk e-Raport</p>
    <p>Tanggal: <?php echo date('d F Y'); ?></p>
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
                        <h3 class="mb-0"><?php echo number_format($totalGaps, 1); ?></h3>
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

        <!-- Grafik DSS01 -->
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

        <!-- Grafik DSS02 -->
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
                                        <li><?php echo $rec; ?></li>
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

    <!-- Action Plan -->
    <!-- <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Rencana Tindak Lanjut</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="bi bi-1-circle me-2 text-primary"></i>Jangka Pendek (0-3 Bulan)</h6>
                            <ul class="small">
                                <li>Dokumentasikan proses yang belum terdokumentasi</li>
                                <li>Tetapkan tim tanggung jawab untuk setiap proses</li>
                                <li>Buat SOP dasar untuk operasional e-Raport</li>
                                <li>Lakukan audit keamanan sistem</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-2-circle me-2 text-warning"></i>Jangka Menengah (3-6 Bulan)</h6>
                            <ul class="small">
                                <li>Implementasikan tools monitoring sistem</li>
                                <li>Lakukan pelatihan untuk tim IT</li>
                                <li>Bangun sistem backup dan recovery</li>
                                <li>Integrasikan proses dengan sistem lain</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="bi bi-3-circle me-2 text-success"></i>Jangka Panjang (6-12 Bulan)</h6>
                            <ul class="small">
                                <li>Capai target capability level 3 untuk semua proses</li>
                                <li>Implementasi framework governance komprehensif</li>
                                <li>Automatisasi proses monitoring dan reporting</li>
                                <li>Continuous improvement cycle</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Print Footer -->
    <div class="print-footer d-none">
        <hr>
        <p class="text-center text-muted">
            Dicetak dari Sistem Analisis Risiko TI berbasis COBIT 2019<br>
            Tanggal: <?php echo date('d F Y H:i:s'); ?>
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
    const dss02Current = chartCurrent[1] ?? 0;
    const dss02Target = chartTarget[1] ?? 4;

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
    const ctxDSS01 = document.getElementById('capabilityChartDSS01').getContext('2d');

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

    // Grafik DSS02
    const ctxDSS02 = document.getElementById('capabilityChartDSS02').getContext('2d');

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
    
    // Print Function
    function printReport() {
        window.print();
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
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
