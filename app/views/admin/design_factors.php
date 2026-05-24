<?php
/**
 * Admin Design Factor Management View
 */

$title = 'Master Data Design Factor';
$pageTitle = 'Master Data Design Factor';

ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Kelola Design Factor COBIT</h5>
                    <small class="text-muted">Tambahkan, ubah, hapus, atau nonaktifkan Design Factor secara dinamis.</small>
                </div>
                <a href="index.php?page=questions" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-list-ul me-1"></i>Kelola Pertanyaan
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Perubahan berhasil disimpan.</div>
                <?php elseif (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Design Factor berhasil dihapus.</div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6><?php echo $editDesignFactor ? 'Ubah Design Factor' : 'Tambah Design Factor Baru'; ?></h6>
                                <form method="POST" action="index.php?page=save-design-factor">
                                    <input type="hidden" name="id" value="<?php echo $editDesignFactor['id'] ?? ''; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Design Factor</label>
                                        <input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars($editDesignFactor['code'] ?? ''); ?>" placeholder="DF1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Design Factor</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($editDesignFactor['name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editDesignFactor['description'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="dfActive" value="1" <?php echo !isset($editDesignFactor['is_active']) || $editDesignFactor['is_active'] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="dfActive">Aktifkan Design Factor</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <?php if ($editDesignFactor): ?>
                                        <a href="index.php?page=design-factors" class="btn btn-link">Batal</a>
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
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($designFactors as $df): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($df['code']); ?></td>
                                            <td><?php echo htmlspecialchars($df['name']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $df['is_active'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $df['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=design-factors&action=edit&id=<?php echo $df['id']; ?>" class="btn btn-sm btn-outline-primary">Ubah</a>
                                                <a href="index.php?page=toggle-design-factor&id=<?php echo $df['id']; ?>&value=<?php echo $df['is_active'] ? 0 : 1; ?>" class="btn btn-sm btn-outline-warning"><?php echo $df['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?></a>
                                                <a href="index.php?page=delete-design-factor&id=<?php echo $df['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus Design Factor ini?');">Hapus</a>
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
