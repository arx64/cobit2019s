<?php
/**
 * Assessment View
 */

$title = 'Data Penilaian';
$pageTitle = 'Data Penilaian';

ob_start();
?>

<!-- <div class="row mb-4">
    <div class="col-12">
        <div class="page-header">
            <h4><i class="bi bi-clipboard-check me-2"></i>Data Penilaian</h4>
            <p class="text-muted mb-0">Lakukan penilaian capability level untuk proses COBIT</p>
        </div>
    </div>
</div> -->

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    <strong>Berhasil!</strong> Penilaian telah disimpan. Silakan lihat <a href="index.php?page=rekomendasi" class="alert-link">halaman rekomendasi</a> untuk hasil analisis.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Process Selector -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Pilih Proses</h5>
            </div>
            <div class="card-body">
                <div class="process-selector d-flex justify-content-evenly flex-wrap gap-2">
                    <?php foreach ($processes as $process): ?>
                    <a href="index.php?page=data-penilaian&process=<?php echo $process['id']; ?>" 
                       class="process-option <?php echo $selectedProcessId == $process['id'] ? 'active' : ''; ?>">
                        <div class="process-code"><?php echo $process['code']; ?></div>
                        <div class="process-name"><?php echo $process['name']; ?></div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assessment Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><?php echo $selectedProcess['code']; ?> - <?php echo $selectedProcess['name']; ?></h5>
                    <small class="text-muted">Jawab pertanyaan berikut untuk menilai capability level</small>
                </div>
                <span class="badge bg-primary">
                    <?php echo count($questions); ?> Pertanyaan
                </span>
            </div>
            <div class="card-body">
                <?php if (!empty($questions)): ?>
                <form method="POST" action="index.php?page=simpan-penilaian" id="assessmentForm">
                    <input type="hidden" name="process_id" value="<?php echo $selectedProcessId; ?>">
                    
                    <div class="questions-list">
                        <?php foreach ($questions as $index => $question): ?>
                        <div class="question-item">
                            <div class="question-header">
                                <span class="question-number"><?php echo $index + 1; ?></span>
                                <span class="question-text"><?php echo $question['question']; ?></span>
                            </div>
                            <div class="rating-options">
                                <?php for ($i = 0; $i <= 5; $i++): 
                                    $isChecked = isset($answers[$question['id']]) && $answers[$question['id']] == $i;
                                ?>
                                <label class="rating-option <?php echo $isChecked ? 'active' : ''; ?>">
                                    <input type="radio" 
                                           name="answers[<?php echo $question['id']; ?>]" 
                                           value="<?php echo $i; ?>"
                                           <?php echo $isChecked ? 'checked' : ''; ?>
                                           required>
                                    <span class="rating-value"><?php echo $i; ?></span>
                                    <span class="rating-label"><?php echo $ratingScale[$i]['label']; ?></span>
                                </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Pastikan semua pertanyaan telah dijawab
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i>Simpan Penilaian
                        </button>
                    </div>
                </form>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x display-4 text-muted"></i>
                    <p class="mt-3 text-muted">Belum ada pertanyaan untuk proses ini.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Rating Scale Reference -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Skala Penilaian</h5>
            </div>
            <div class="card-body p-0">
                <div class="rating-scale-list">
                    <?php foreach ($ratingScale as $value => $scale): ?>
                    <div class="rating-scale-item">
                        <span class="rating-badge bg-<?php echo $value == 0 ? 'secondary' : ($value <= 2 ? 'danger' : ($value <= 3 ? 'warning' : ($value == 4 ? 'info' : 'success'))); ?>">
                            <?php echo $value; ?>
                        </span>
                        <div class="rating-detail">
                            <strong><?php echo $scale['label']; ?></strong>
                            <small class="d-block text-muted"><?php echo $scale['desc']; ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Progress Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Progress Penilaian</h5>
            </div>
            <div class="card-body">
                <?php
                $answeredCount = count($answers);
                $totalCount = count($questions);
                $progressPercent = $totalCount > 0 ? ($answeredCount / $totalCount) * 100 : 0;
                ?>
                <div class="progress-info">
                    <span><?php echo $answeredCount; ?> dari <?php echo $totalCount; ?> dijawab</span>
                    <span class="fw-semibold"><?php echo round($progressPercent); ?>%</span>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-primary" role="progressbar" 
                         style="width: <?php echo $progressPercent; ?>%">
                    </div>
                </div>
                
                <?php if ($answeredCount > 0): ?>
                <?php
                $totalValue = array_sum($answers);
                $currentLevel = $answeredCount > 0 ? round($totalValue / $answeredCount, 2) : 0;
                ?>
                <div class="current-level mt-3">
                    <div class="text-center">
                        <span class="text-muted small">Capability Level Saat Ini</span>
                        <div class="display-4 fw-bold text-primary"><?php echo number_format($currentLevel, 2); ?></div>
                        <span class="badge bg-<?php echo $currentLevel < 2 ? 'danger' : ($currentLevel < 3 ? 'warning' : ($currentLevel < 4 ? 'info' : 'success')); ?>">
                            Target: 4.0
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.process-selector {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.process-option {
    display: flex;
    flex-direction: column;
    padding: 1rem 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s ease;
    min-width: 200px;
}
.process-option:hover {
    border-color: #0d6efd;
    text-decoration: none;
    color: #0d6efd;
}
.process-option.active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
.process-code {
    font-size: 1.25rem;
    font-weight: 700;
}
.process-name {
    font-size: 0.875rem;
}

.question-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}
.question-header {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
}
.question-number {
    background: #0d6efd;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}
.question-text {
    font-weight: 500;
    line-height: 1.5;
}

.rating-options {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.rating-option {
    flex: 1;
    min-width: 80px;
    text-align: center;
    padding: 0.75rem 0.5rem;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.rating-option:hover {
    border-color: #0d6efd;
    background: #f8f9fa;
}
.rating-option.active {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
.rating-option input {
    display: none;
}
.rating-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
}
.rating-label {
    display: block;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.rating-scale-list {
    padding: 0;
}
.rating-scale-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}
.rating-scale-item:last-child {
    border-bottom: none;
}
.rating-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-right: 1rem;
    flex-shrink: 0;
}
.rating-detail {
    flex: 1;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle rating option click
    const ratingOptions = document.querySelectorAll('.rating-option');
    ratingOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from siblings
            const siblings = this.parentElement.querySelectorAll('.rating-option');
            siblings.forEach(s => s.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Check the radio input
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
});
</script>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
