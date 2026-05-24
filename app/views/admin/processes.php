<?php
/**
 * Admin Process Management View
 */

$title = 'Master Data Proses';
$pageTitle = 'Master Data Proses';

ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-bookmark-fill me-2"></i>Kelola Domain / Proses COBIT</h5>
                    <small class="text-muted">Tambahkan, ubah, hapus, atau nonaktifkan proses COBIT secara dinamis.</small>
                </div>
                <a href="index.php?page=questions" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-list-ul me-1"></i>Kelola Pertanyaan
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Perubahan berhasil disimpan.</div>
                <?php elseif (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">Proses berhasil dihapus.</div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6><?php echo $editProcess ? 'Ubah Proses' : 'Tambah Proses Baru'; ?></h6>
                                <form method="POST" action="index.php?page=save-process">
                                    <input type="hidden" name="id" value="<?php echo $editProcess['id'] ?? ''; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Proses</label>
                                        <input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars($editProcess['code'] ?? ''); ?>" placeholder="DSS01" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Proses</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($editProcess['name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($editProcess['description'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="processActive" value="1" <?php echo !isset($editProcess['is_active']) || $editProcess['is_active'] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="processActive">Aktifkan proses</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <?php if ($editProcess): ?>
                                        <a href="index.php?page=processes" class="btn btn-link">Batal</a>
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
                                    <?php foreach ($processes as $process): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($process['code']); ?></td>
                                            <td><?php echo htmlspecialchars($process['name']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $process['is_active'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $process['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=processes&action=edit&id=<?php echo $process['id']; ?>" class="btn btn-sm btn-outline-primary">Ubah</a>
                                                <a href="index.php?page=toggle-process&id=<?php echo $process['id']; ?>&value=<?php echo $process['is_active'] ? 0 : 1; ?>" class="btn btn-sm btn-outline-warning"><?php echo $process['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?></a>
                                                <a href="index.php?page=delete-process&id=<?php echo $process['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus proses ini?');">Hapus</a>
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
