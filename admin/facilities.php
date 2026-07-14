<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db = Database::connect();

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM facilities WHERE id=?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'Fasilitas berhasil dihapus.');
    redirect('facilities.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = clean($_POST['facility_name'] ?? '');
    $icon = clean($_POST['icon'] ?? '');

    if (empty($name) || empty($icon)) {
        setFlash('error', 'Nama fasilitas dan icon wajib diisi.');
        redirect('facilities.php');
    }

    if ($id > 0) {
        $stmt = $db->prepare("UPDATE facilities SET facility_name=?, icon=? WHERE id=?");
        $stmt->execute([$name, $icon, $id]);
        setFlash('success', 'Fasilitas berhasil diperbarui.');
    } else {
        $stmt = $db->prepare("INSERT INTO facilities (facility_name, icon) VALUES (?, ?)");
        $stmt->execute([$name, $icon]);
        setFlash('success', 'Fasilitas baru berhasil ditambahkan.');
    }
    redirect('facilities.php');
}

$facilities = $db->query("SELECT * FROM facilities ORDER BY id ASC")->fetchAll();

$pageTitle = 'Kelola Fasilitas';
require_once __DIR__ . '/includes/header.php';
?>

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#facilityModal" onclick="resetForm()">
    <i class="fa-solid fa-plus"></i> Tambah Fasilitas
</button>

<div class="card border-0 shadow-sm">
<div class="table-responsive">
<table class="table align-middle mb-0">
    <thead class="table-light">
        <tr><th>Icon</th><th>Nama Fasilitas</th><th>Class Icon</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($facilities as $f): ?>
        <tr>
            <td><i class="fa-solid <?= clean($f['icon']) ?> fs-5 text-success"></i></td>
            <td><?= clean($f['facility_name']) ?></td>
            <td><code><?= clean($f['icon']) ?></code></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#facilityModal"
                    onclick='openEdit(<?= json_encode($f) ?>)'>
                    <i class="fa-solid fa-pen"></i>
                </button>
                <a href="facilities.php?delete=<?= (int)$f['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus fasilitas ini?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($facilities)): ?>
        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data fasilitas.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>

<div class="modal fade" id="facilityModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
      <div class="modal-header">
        <h5 class="modal-title" id="facModalTitle">Tambah Fasilitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="fac_id">
        <div class="mb-3">
            <label class="form-label">Nama Fasilitas</label>
            <input type="text" name="facility_name" id="fac_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Class Icon (Font Awesome)</label>
            <input type="text" name="icon" id="fac_icon" class="form-control" placeholder="contoh: fa-wifi" required>
            <div class="form-text">Lihat daftar icon di <a href="https://fontawesome.com/icons" target="_blank">fontawesome.com/icons</a>, salin nama class-nya (tanpa "fa-solid").</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
function resetForm() {
    document.getElementById('fac_id').value = '';
    document.getElementById('fac_name').value = '';
    document.getElementById('fac_icon').value = '';
    document.getElementById('facModalTitle').innerText = 'Tambah Fasilitas';
}
function openEdit(data) {
    document.getElementById('fac_id').value = data.id;
    document.getElementById('fac_name').value = data.facility_name;
    document.getElementById('fac_icon').value = data.icon;
    document.getElementById('facModalTitle').innerText = 'Edit Fasilitas';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
