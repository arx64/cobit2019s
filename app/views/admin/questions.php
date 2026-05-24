<?php
/**
 * Admin Question Management View
 */

$title = 'Master Data Pertanyaan';
$pageTitle = 'Master Data Pertanyaan';

ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Kelola Pertanyaan Assessment</h5>
                    <small class="text-muted">Tambahkan, ubah, hapus, atau nonaktifkan pertanyaan dan bobotnya.</small>
                </div>
                <a href="index.php?page=processes" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-bookmark-fill me-1"></i>Kelola Proses
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Perubahan berhasil disimpan.</div>
                <?php elseif (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Pertanyaan berhasil dihapus.</div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6><?php echo $editQuestion ? 'Ubah Pertanyaan' : 'Tambah Pertanyaan Baru'; ?></h6>
                                <form method="POST" action="index.php?page=save-question">
                                    <input type="hidden" name="id" value="<?php echo $editQuestion['id'] ?? ''; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Proses</label>
                                        <select name="process_id" class="form-select" required>
                                            <option value="">Pilih Proses</option>
                                            <?php foreach ($processes as $process): ?>
                                                <option value="<?php echo $process['id']; ?>" <?php echo isset($editQuestion['process_id']) && $editQuestion['process_id'] == $process['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($process['code'] . ' - ' . $process['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pertanyaan</label>
                                        <textarea name="question" class="form-control" rows="3" required><?php echo htmlspecialchars($editQuestion['question'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Referensi Praktik</label>
                                        <input type="text" name="practice_reference" class="form-control" value="<?php echo htmlspecialchars($editQuestion['practice_reference'] ?? ''); ?>" placeholder="DSS01.01">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bobot</label>
                                        <input type="number" name="weight" class="form-control" min="1" value="<?php echo htmlspecialchars($editQuestion['weight'] ?? 1); ?>" required>
                                        <div class="form-text">Gunakan bobot jika pertanyaan memiliki kepentingan berbeda. Default adalah 1.</div>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="questionActive" value="1" <?php echo !isset($editQuestion['is_active']) || $editQuestion['is_active'] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="questionActive">Aktifkan pertanyaan</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <?php if ($editQuestion): ?>
                                        <a href="index.php?page=questions" class="btn btn-link">Batal</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Proses</th>
                                        <th>Pertanyaan</th>
                                        <th>Referensi</th>
                                        <th>Bobot</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($questions as $question): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($question['process_code']); ?></td>
                                            <td><?php echo htmlspecialchars($question['question']); ?></td>
                                            <td><?php echo htmlspecialchars($question['practice_reference']); ?></td>
                                            <td><?php echo htmlspecialchars($question['weight']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $question['is_active'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $question['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=questions&action=edit&id=<?php echo $question['id']; ?>" class="btn btn-sm btn-outline-primary">Ubah</a>
                                                <a href="index.php?page=toggle-question&id=<?php echo $question['id']; ?>&value=<?php echo $question['is_active'] ? 0 : 1; ?>" class="btn btn-sm btn-outline-warning"><?php echo $question['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?></a>
                                                <a href="index.php?page=delete-question&id=<?php echo $question['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus pertanyaan ini?');">Hapus</a>
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
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'app/views/layouts/main.php';
