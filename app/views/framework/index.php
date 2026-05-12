<?php

/**
 * Framework COBIT View
 */

$title = 'Framework COBIT';
$pageTitle = 'Framework COBIT';

ob_start();
?>

<!-- <div class="row mb-4">
    <div class="col-12">
        <div class="page-header">
            <h4><i class="bi bi-diagram-3 me-2"></i>Framework COBIT 2019</h4>
            <p class="text-muted mb-0">Proses-proses yang dianalisis dalam sistem e-Raport</p>
        </div>
    </div>
</div> -->

<!-- Overview Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="info-icon bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-info-lg fs-4"></i>
                    </div>
                    <div>
                        <h5>Tentang COBIT 2019</h5>
                        <p class="text-muted mb-0">
                            COBIT (Control Objectives for Information and Related Technologies) adalah framework
                            governance dan manajemen TI yang menyediakan praktik-praktik terbaik untuk mengelola
                            dan mengendalikan proses-proses TI. Dalam konteks sistem Analisis Pengelolaan Layanan Kantor Desa Bogak Besar, COBIT 2019
                            digunakan untuk menilai kematangan proses TI dan mengidentifikasi area perbaikan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Cards -->
<!-- <div class="row g-4">
    <?php foreach ($processes as $process): ?>
    <?php
        $code = $process['code'];
        $desc = $processDescriptions[$code] ?? null;
    ?>
    <div class="col-lg-6">
        <div class="card process-card h-100">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo $process['code']; ?> - <?php echo $process['name']; ?></h5>
                    <span class="badge bg-light text-primary">Domain: DSS</span>
                </div>
            </div>
            <div class="card-body">
                <?php if ($desc): ?>
                <div class="process-purpose mb-4">
                    <h6 class="text-primary"><i class="bi bi-bullseye me-2"></i>Tujuan</h6>
                    <p class="text-muted"><?php echo $desc['purpose']; ?></p>
                </div>
                
                <div class="process-components mb-4">
                    <h6 class="text-primary"><i class="bi bi-layers me-2"></i>Komponen Proses</h6>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($desc['components'] as $component): ?>
                        <li class="list-group-item px-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i><?php echo $component; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="process-practices">
                    <h6 class="text-primary"><i class="bi bi-gear me-2"></i>Praktik Kunci</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($desc['practices'] as $practice): ?>
                        <span class="badge bg-light text-dark border"><?php echo $practice; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <p class="text-muted">Deskripsi untuk proses ini sedang dalam pengembangan.</p>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-light">
                <a href="index.php?page=data-penilaian&process=<?php echo $process['id']; ?>" 
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-clipboard-check me-1"></i> Lakukan Penilaian
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div> -->
<!-- Domain COBIT Accordion -->
<div class="row">
    <div class="col-12">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Daftar Domain COBIT 2019
                </h5>
            </div>

            <div class="card-body">

                <div class="accordion" id="cobitAccordion">

                    <!-- DSS -->
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="headingDSS">
                            <button class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseDSS">

                                <div>
                                    <strong>DSS</strong><br>
                                    <small>Deliver, Service, and Support</small>
                                </div>

                            </button>
                        </h2>

                        <div id="collapseDSS"
                            class="accordion-collapse collapse show"
                            data-bs-parent="#cobitAccordion">

                            <div class="accordion-body">

                                <?php foreach ($processes as $process): ?>

                                    <?php if (strpos($process['code'], 'DSS') === 0): ?>

                                        <div class="border rounded p-3 mb-3">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <div>
                                                    <h6 class="mb-1">
                                                        <?php echo $process['code']; ?>
                                                    </h6>

                                                    <small class="text-muted">
                                                        <?php echo $process['name']; ?>
                                                    </small>
                                                </div>

                                                <a href="index.php?page=data-penilaian&process=<?php echo $process['id']; ?>"
                                                    class="btn btn-sm btn-primary">

                                                    Penilaian

                                                </a>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </div>

                        </div>
                    </div>


                    <!-- APO -->
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="headingAPO">

                            <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseAPO">

                                <div>
                                    <strong>APO</strong><br>
                                    <small>Align, Plan, and Organize</small>
                                </div>

                            </button>

                        </h2>

                        <div id="collapseAPO"
                            class="accordion-collapse collapse"
                            data-bs-parent="#cobitAccordion">

                            <div class="accordion-body">

                                <p class="text-muted mb-0">
                                    Domain APO membahas strategi,
                                    perencanaan, dan tata kelola TI.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- MEA -->
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="headingMEA">

                            <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseMEA">

                                <div>
                                    <strong>MEA</strong><br>
                                    <small>Monitor, Evaluate, and Assess</small>
                                </div>

                            </button>

                        </h2>

                        <div id="collapseMEA"
                            class="accordion-collapse collapse"
                            data-bs-parent="#cobitAccordion">

                            <div class="accordion-body">

                                <p class="text-muted mb-0">
                                    Domain MEA fokus pada monitoring,
                                    evaluasi, dan penilaian performa TI.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- EDM -->
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="headingEDM">

                            <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseEDM">

                                <div>
                                    <strong>EDM</strong><br>
                                    <small>Evaluate, Direct, and Monitor</small>
                                </div>

                            </button>

                        </h2>

                        <div id="collapseEDM"
                            class="accordion-collapse collapse"
                            data-bs-parent="#cobitAccordion">

                            <div class="accordion-body">

                                <p class="text-muted mb-0">
                                    Domain EDM berkaitan dengan governance
                                    dan pengawasan TI.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- BAI -->
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="headingBAI">

                            <button class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseBAI">

                                <div>
                                    <strong>BAI</strong><br>
                                    <small>Build, Acquire, and Implement</small>
                                </div>

                            </button>

                        </h2>

                        <div id="collapseBAI"
                            class="accordion-collapse collapse"
                            data-bs-parent="#cobitAccordion">

                            <div class="accordion-body">

                                <p class="text-muted mb-0">
                                    Domain BAI fokus pada pembangunan,
                                    implementasi, dan perubahan sistem TI.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<!-- Capability Levels Reference -->
<!-- <div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart-steps me-2"></i>Skala Capability Level COBIT</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="capability-level-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-secondary me-2">0</span>
                                <h6 class="mb-0">Incomplete</h6>
                            </div>
                            <p class="small text-muted mb-0">Praktik tidak dilaksanakan atau gagal mencapai tujuan proses.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="capability-level-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-danger me-2">1</span>
                                <h6 class="mb-0">Performed</h6>
                            </div>
                            <p class="small text-muted mb-0">Praktik baru mulai diterapkan, mencapai tujuan proses dasar.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="capability-level-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-warning me-2">2</span>
                                <h6 class="mb-0">Managed</h6>
                            </div>
                            <p class="small text-muted mb-0">Praktik terdokumentasi, direncanakan, dan dipantau.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="capability-level-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-info me-2">3</span>
                                <h6 class="mb-0">Established</h6>
                            </div>
                            <p class="small text-muted mb-0">Praktik standar dan terintegrasi dengan proses organisasi.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="capability-level-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">4</span>
                                <h6 class="mb-0">Predictable</h6>
                            </div>
                            <p class="small text-muted mb-0">Praktik terukur, dikendalikan, dan diprediksi hasilnya.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="capability-level-item">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-success me-2">5</span>
                                <h6 class="mb-0">Optimizing</h6>
                            </div>
                            <p class="small text-muted mb-0">Praktik terus diperbaiki berdasarkan metrik dan inovasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
