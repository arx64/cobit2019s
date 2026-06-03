<?php

/**
 * Design Factor View
 */

$title = 'Design Factor';
$pageTitle = 'Design Factor COBIT';

ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="page-header">
            <h4><i class="bi bi-sliders me-2"></i>Design Factor COBIT 2019</h4>
            <p class="text-muted mb-0">Faktor-faktor desain yang memengaruhi sistem governance TI</p>
        </div>
    </div>
</div>


<!-- Header -->
<div class="row mb-4">

    <div class="col-12">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-sliders fs-4"></i>
                    </div>

                    <div>
                        <h4 class="mb-1">Design Factor COBIT 2019</h4>

                        <p class="text-muted mb-0">
                            Faktor desain yang memengaruhi tata kelola
                            dan manajemen TI pada Analisis Pengelolaan Layanan Desa Bogak Besar.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- Tabel Design Factor -->
<div class="row">

    <div class="col-12">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>
                    Daftar Design Factor
                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle text-center">

                        <thead class="table-light">

                            <tr>
                                <th width="15%">Kode DF</th>
                                <th width="25%">Nama DF</th>
                                <th width="35%">Deskripsi</th>
                                <th width="15%">Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (!empty($designFactors)): ?>
                                <?php foreach ($designFactors as $df): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($df['code']); ?></strong></td>
                                        <td class="text-start"><?php echo htmlspecialchars($df['name']); ?></td>
                                        <td class="text-start"><?php echo htmlspecialchars($df['description']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $df['is_active'] ? 'success' : 'secondary'; ?>">
                                                <?php echo $df['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Belum ada Design Factor yang tersedia. Silakan hubungi admin untuk menambahkan.</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
