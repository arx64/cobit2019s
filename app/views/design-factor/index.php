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

<!-- Overview Card -->
<!-- <div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="info-icon bg-info text-white rounded-circle p-3 me-3">
                        <i class="bi bi-lightbulb fs-4"></i>
                    </div>
                    <div>
                        <h5>Apa itu Design Factor?</h5>
                        <p class="text-muted mb-0">
                            Design Factors adalah faktor-faktor kontekstual yang memengaruhi desain sistem 
                            governance dan manajemen TI dalam organisasi. Faktor-faktor ini membantu 
                            menyesuaikan framework COBIT dengan kebutuhan spesifik organisasi. 
                            Dalam konteks sekolah dan sistem e-Raport, faktor-faktor ini menjadi 
                            pertimbangan penting dalam merancang governance system.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Design Factor Cards -->
<!-- <div class="row g-4">
    <?php foreach ($designFactors as $df): ?>
    <?php
        $code = $df['code'];
        $detail = $dfDetails[$code] ?? null;
    ?>
    <div class="col-lg-6">
        <div class="card design-factor-card h-100">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <span class="df-code"><?php echo $df['code']; ?></span>
                    <h5 class="mb-0 ms-2"><?php echo $df['name']; ?></h5>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted"><?php echo $df['description']; ?></p>
                
                <?php if ($detail): ?>
                    <?php if (isset($detail['focus_areas'])): ?>
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-bullseye me-2"></i>Focus Areas</h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($detail['focus_areas'] as $area): ?>
                            <li><i class="bi bi-check2 text-success me-2"></i><?php echo $area; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['e_raport_context'])): ?>
                    <div class="context-box bg-light p-3 rounded">
                        <h6 class="text-info"><i class="bi bi-building me-2"></i>Konteks e-Raport</h6>
                        <ul class="list-unstyled mb-0 small">
                            <?php foreach ($detail['e_raport_context'] as $context): ?>
                            <li><i class="bi bi-arrow-right-short text-info me-1"></i><?php echo $context; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['risk_categories'])): ?>
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-shield-exclamation me-2"></i>Kategori Risiko</h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($detail['risk_categories'] as $risk): ?>
                            <li><i class="bi bi-exclamation-triangle text-warning me-2"></i><?php echo $risk; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['e_raport_risks'])): ?>
                    <div class="context-box bg-light p-3 rounded">
                        <h6 class="text-warning"><i class="bi bi-exclamation-diamond me-2"></i>Risiko e-Raport</h6>
                        <ul class="list-unstyled mb-0 small">
                            <?php foreach ($detail['e_raport_risks'] as $risk): ?>
                            <li><i class="bi bi-arrow-right-short text-warning me-1"></i><?php echo $risk; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['common_issues'])): ?>
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-tools me-2"></i>Isu Umum</h6>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($detail['common_issues'] as $issue): ?>
                            <li><i class="bi bi-wrench text-secondary me-2"></i><?php echo $issue; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['e_raport_issues'])): ?>
                    <div class="context-box bg-light p-3 rounded">
                        <h6 class="text-secondary"><i class="bi bi-building me-2"></i>Isu e-Raport</h6>
                        <ul class="list-unstyled mb-0 small">
                            <?php foreach ($detail['e_raport_issues'] as $issue): ?>
                            <li><i class="bi bi-arrow-right-short text-secondary me-1"></i><?php echo $issue; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['it_roles'])): ?>
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-person-gear me-2"></i>Peran TI</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($detail['it_roles'] as $role): ?>
                            <span class="badge bg-light text-dark border"><?php echo $role; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($detail['school_it_role'])): ?>
                    <div class="context-box bg-light p-3 rounded">
                        <h6 class="text-primary"><i class="bi bi-building me-2"></i>Peran TI di Sekolah</h6>
                        <div class="small text-muted">
                            <?php echo $detail['school_it_role']; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div> -->

<!-- Design Factor Impact -->
<!-- <div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Dampak Design Factor terhadap e-Raport</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3 text-center">
                        <div class="impact-icon bg-primary text-white rounded-circle mx-auto mb-3">
                            <i class="bi bi-bullseye fs-2"></i>
                        </div>
                        <h6>Enterprise Goals</h6>
                        <p class="small text-muted">Menentukan prioritas dan alokasi sumber daya TI untuk e-Raport</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="impact-icon bg-warning text-white rounded-circle mx-auto mb-3">
                            <i class="bi bi-shield-exclamation fs-2"></i>
                        </div>
                        <h6>Risk Profile</h6>
                        <p class="small text-muted">Mengidentifikasi risiko spesifik yang perlu dikelola dalam sistem e-Raport</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="impact-icon bg-info text-white rounded-circle mx-auto mb-3">
                            <i class="bi bi-exclamation-triangle fs-2"></i>
                        </div>
                        <h6>I&T Issues</h6>
                        <p class="small text-muted">Isu-isu teknis yang memengaruhi implementasi dan operasional e-Raport</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="impact-icon bg-success text-white rounded-circle mx-auto mb-3">
                            <i class="bi bi-person-badge fs-2"></i>
                        </div>
                        <h6>Role of IT</h6>
                        <p class="small text-muted">Menentukan tingkat keterlibatan dan investasi TI dalam pendidikan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

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
                            dan manajemen TI pada sistem e-Raport.
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
                                <th width="12%">Kode DF</th>
                                <th width="25%">Nama DF</th>
                                <th width="25%">DF Layanan Desa</th>
                                <th>Keterangan</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $dfList = [

                                ['code' => 'DF1',  'name' => 'Enterprise Strategy'],
                                ['code' => 'DF2',  'name' => 'Enterprise Goals'],
                                ['code' => 'DF3',  'name' => 'Risk Profile'],
                                ['code' => 'DF4',  'name' => 'I&T Related Issues'],
                                ['code' => 'DF5',  'name' => 'Threat Landscape'],
                                ['code' => 'DF6',  'name' => 'Compliance Requirements'],
                                ['code' => 'DF7',  'name' => 'Role of IT'],
                                ['code' => 'DF8',  'name' => 'Sourcing Model'],
                                ['code' => 'DF9',  'name' => 'IT Implementation Methods'],
                                ['code' => 'DF10', 'name' => 'Technology Adoption Strategy'],
                                ['code' => 'DF11', 'name' => 'Enterprise Size']

                            ];

                            $checkedDF = [
                                'DF2',
                                'DF3',
                                'DF11'
                            ];

                            ?>

                            <?php foreach ($dfList as $df): ?>

                                <tr>

                                    <td>
                                        <strong>
                                            <?php echo $df['code']; ?>
                                        </strong>
                                    </td>

                                    <td class="text-start">
                                        <?php echo $df['name']; ?>
                                    </td>

                                    <td>

                                        <?php if (in_array($df['code'], $checkedDF)): ?>

                                            <i class="bi bi-check-lg text-success fs-4"></i>

                                        <?php endif; ?>

                                    </td>

                                    <td class="text-start">

                                        <?php

                                $descriptions = [

                                    'DF1'  => 'Strategi organisasi dalam pengelolaan layanan dan teknologi informasi.',

                                    'DF2'  => 'Menentukan tujuan utama layanan desa dan kebutuhan tata kelola TI.',

                                    'DF3'  => 'Mengidentifikasi risiko layanan, keamanan data, dan operasional sistem.',

                                    'DF4'  => 'Permasalahan dan isu TI yang memengaruhi layanan organisasi.',

                                    'DF5'  => 'Ancaman lingkungan TI seperti serangan siber dan gangguan sistem.',

                                    'DF6'  => 'Kebutuhan kepatuhan terhadap regulasi dan kebijakan organisasi.',

                                    'DF7'  => 'Peran TI dalam mendukung proses bisnis dan layanan organisasi.',

                                    'DF8'  => 'Model pengadaan atau penggunaan layanan TI dari pihak internal maupun eksternal.',

                                    'DF9'  => 'Metode implementasi dan pengembangan sistem teknologi informasi.',

                                    'DF10' => 'Strategi organisasi dalam mengadopsi teknologi baru.',

                                    'DF11' => 'Penyesuaian tata kelola berdasarkan ukuran dan kapasitas organisasi.'

                                ];

                                echo $descriptions[$df['code']] ?? '-';

                                        ?>

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

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
