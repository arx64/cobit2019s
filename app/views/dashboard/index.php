<?php

/**
 * Dashboard View
 */

$title = 'Dashboard';
$pageTitle = 'Dashboard';

ob_start();
?>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="d-flex align-items-center">
                <div class="welcome-icon">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>
                <div class="ms-3">
                    <h4 class="mb-1">Selamat Datang, <?php echo $_SESSION['user_name'] ?? 'User'; ?>!</h4>
                    <p class="mb-0 text-muted">Sistem Analisis Pengelolaan Layanan Desa Bogak Besar berbasis COBIT 2019</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Peran Admin / Operator</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Admin</strong> adalah pengguna yang memiliki hak untuk mengelola master data sistem, termasuk domain/proses COBIT, pertanyaan assessment, bobot penilaian, dan responden.
                    </p>
                    <ul>
                        <li>Mengelola domain/proses COBIT (tambah, ubah, hapus, aktif/nonaktif).</li>
                        <li>Mengelola pertanyaan assessment serta bobot dan status aktifnya.</li>
                        <li>Mengelola responden dan melihat hasil penilaian.</li>
                        <li>Memastikan data penilaian sesuai tanggal dan perhitungan capability level akurat.</li>
                    </ul>
                    <p class="mb-0"><strong>Catatan:</strong> Setiap perubahan master data harus dilakukan oleh admin untuk menjaga konsistensi perhitungan dan laporan.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Charts Section -->
<div id="chartsSection" class="row mb-4 no-print">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Grafik Capability Level DSS01</h5>
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
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Grafik Capability Level DSS02</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="capabilityChartDSS02"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assessment Results -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Hasil Penilaian</h5>
                <a href="index.php?page=rekomendasi" class="btn btn-sm btn-primary">Lihat Detail</a>
            </div>
            <div class="card-body">
                <?php if (!empty($results)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Proses</th>
                                    <th>Capability Level</th>
                                    <th>Gap</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                    <?php
                                    // `recommendation` can be stored as JSON string (from DB) or already as array
                                    if (is_string($result['recommendation'])) {
                                        $recData = json_decode($result['recommendation'], true) ?: [];
                                    } elseif (is_array($result['recommendation'])) {
                                        $recData = $result['recommendation'];
                                    } else {
                                        $recData = [];
                                    }
                                    $statusClass = $recData['color'] ?? 'secondary';
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $result['process_code']; ?></strong>
                                            <br><small class="text-muted"><?php echo $result['process_name']; ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-<?php echo $statusClass; ?>"
                                                        style="width: <?php echo ($result['capability_level'] / 5) * 100; ?>%">
                                                    </div>
                                                </div>
                                                <span class="ms-2 small fw-semibold"><?php echo number_format($result['capability_level'], 2); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo $result['gap']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <?php echo $recData['level'] ?? 'N/A'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-x display-4 text-muted"></i>
                        <p class="mt-3 text-muted">Belum ada data penilaian. Silakan lakukan penilaian terlebih dahulu.</p>
                        <a href="index.php?page=data-penilaian" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-lg me-2"></i>Mulai Penilaian
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart Data per domain
    const dss01Labels = ['Current Level', 'Target Level'];
    const dss01Current = <?php echo json_encode($chartCurrent[0] ?? 0); ?>;
    const dss01Target = <?php echo json_encode($chartTarget[0]  ?? 4); ?>;

    const dss02Labels = ['Current Level', 'Target Level'];
    const dss02Current = <?php echo json_encode($chartCurrent[1] ?? 0); ?>;
    const dss02Target = <?php echo json_encode($chartTarget[1]  ?? 4); ?>;

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

    // Grafik Capability Level DSS01
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

    // Grafik Capability Level DSS02
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
</script>
<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
