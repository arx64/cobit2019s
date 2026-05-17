<?php
/**
 * Respondent View
 */

$title = 'Manajemen Responden';
$pageTitle = 'Manajemen Responden';

ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Manajemen Responden</h5>
                <span class="badge bg-secondary"><?php echo count($respondents); ?> Responden</span>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Data responden berhasil disimpan.</div>
                <?php endif; ?>
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Data responden berhasil dihapus.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Nama dan Jabatan harus diisi.</div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=save-respondent">
                    <input type="hidden" name="id" value="<?php echo $editRespondent['id'] ?? ''; ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Responden</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($editRespondent['name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jabatan / Peran</label>
                            <input type="text" name="position" class="form-control" value="<?php echo htmlspecialchars($editRespondent['position'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="operator_sistem" <?php echo isset($editRespondent['category']) && $editRespondent['category'] === 'operator_sistem' ? 'selected' : ''; ?>>Operator Sistem</option>
                                <option value="perangkat_desa" <?php echo isset($editRespondent['category']) && $editRespondent['category'] === 'perangkat_desa' ? 'selected' : ''; ?>>Perangkat Desa</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i><?php echo isset($editRespondent) ? 'Update Responden' : 'Tambah Responden'; ?>
                        </button>
                        <?php if (isset($editRespondent)): ?>
                        <a href="index.php?page=respondents" class="btn btn-outline-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Responden</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Jabatan / Peran</th>
                                <th>Kategori</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($respondents)): ?>
                                <?php foreach ($respondents as $index => $respondent): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($respondent['name']); ?></td>
                                        <td><?php echo htmlspecialchars($respondent['position']); ?></td>
                                        <td><?php echo $respondent['category'] === 'operator_sistem' ? 'Operator Sistem' : 'Perangkat Desa'; ?></td>
                                        <td class="text-end">
                                            <a href="index.php?page=respondents&action=edit&id=<?php echo $respondent['id']; ?>" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                            <a href="index.php?page=delete-respondent&id=<?php echo $respondent['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus responden ini?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada responden.</td>
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
