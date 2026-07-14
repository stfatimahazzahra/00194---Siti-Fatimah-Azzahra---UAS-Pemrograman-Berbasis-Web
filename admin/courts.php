<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db = Database::connect();

// ==== HANDLE DELETE ====
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $db->prepare("SELECT image FROM courts WHERE id = ?");
    $stmt->execute([$id]);
    $court = $stmt->fetch();
    if ($court) {
        $db->prepare("DELETE FROM courts WHERE id = ?")->execute([$id]);
        if ($court['image'] && file_exists(__DIR__ . '/../assets/uploads/courts/' . $court['image'])) {
            unlink(__DIR__ . '/../assets/uploads/courts/' . $court['image']);
        }
        setFlash('success', 'Lapangan berhasil dihapus.');
    }
    redirect('courts.php');
}

// ==== HANDLE CREATE / UPDATE ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = clean($_POST['name'] ?? '');
    $location = clean($_POST['location'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'available';
    $facilityIds = $_POST['facilities'] ?? [];

    if (empty($name) || empty($location) || $price <= 0) {
        setFlash('error', 'Nama, lokasi, dan harga wajib diisi dengan benar.');
        redirect('courts.php');
    }

    // Upload gambar (opsional)
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $imageName = 'court_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/uploads/courts/' . $imageName);
        }
    }

    if ($id > 0) {
        // UPDATE
        if ($imageName) {
            $stmt = $db->prepare("UPDATE courts SET name=?, location=?, price=?, description=?, status=?, image=? WHERE id=?");
            $stmt->execute([$name, $location, $price, $description, $status, $imageName, $id]);
        } else {
            $stmt = $db->prepare("UPDATE courts SET name=?, location=?, price=?, description=?, status=? WHERE id=?");
            $stmt->execute([$name, $location, $price, $description, $status, $id]);
        }
        $db->prepare("DELETE FROM court_facilities WHERE court_id=?")->execute([$id]);
        $courtId = $id;
        setFlash('success', 'Data lapangan berhasil diperbarui.');
    } else {
        // CREATE
        $stmt = $db->prepare("INSERT INTO courts (name, location, price, description, image, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $location, $price, $description, $imageName, $status]);
        $courtId = $db->lastInsertId();
        setFlash('success', 'Lapangan baru berhasil ditambahkan.');
    }

    if (!empty($facilityIds)) {
        $stmt = $db->prepare("INSERT INTO court_facilities (court_id, facility_id) VALUES (?, ?)");
        foreach ($facilityIds as $fid) {
            $stmt->execute([$courtId, (int)$fid]);
        }
    }

    redirect('courts.php');
}

$courts = $db->query("SELECT * FROM courts ORDER BY id ASC")->fetchAll();
$allFacilities = $db->query("SELECT * FROM facilities ORDER BY facility_name ASC")->fetchAll();

// Ambil fasilitas per lapangan untuk keperluan edit
$courtFacilityMap = [];
$rows = $db->query("SELECT court_id, facility_id FROM court_facilities")->fetchAll();
foreach ($rows as $r) {
    $courtFacilityMap[$r['court_id']][] = $r['facility_id'];
}

$pageTitle = 'Kelola Lapangan';
require_once __DIR__ . '/includes/header.php';
?>

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#courtModal" onclick="openCreateModal()">
    <i class="fa-solid fa-plus"></i> Tambah Lapangan
</button>

<div class="card border-0 shadow-sm">
<div class="table-responsive">
<table class="table align-middle mb-0">
    <thead class="table-light">
        <tr><th>Foto</th><th>Nama</th><th>Lokasi</th><th>Harga/jam</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($courts as $c): ?>
        <tr>
            <td><img src="../assets/uploads/courts/<?= clean($c['image']) ?>" onerror="this.src='https://placehold.co/80x60?text=No+Img'" style="width:70px;height:50px;object-fit:cover;border-radius:6px"></td>
            <td><?= clean($c['name']) ?></td>
            <td><?= clean($c['location']) ?></td>
            <td><?= rupiah($c['price']) ?></td>
            <td><span class="badge <?= $c['status']==='available' ? 'bg-success' : 'bg-danger' ?>"><?= $c['status']==='available' ? 'Tersedia' : 'Maintenance' ?></span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary"
                    onclick='openEditModal(<?= json_encode([
                        "id" => $c["id"], "name" => $c["name"], "location" => $c["location"],
                        "price" => $c["price"], "description" => $c["description"], "status" => $c["status"],
                        "facilities" => $courtFacilityMap[$c["id"]] ?? []
                    ]) ?>)'>
                    <i class="fa-solid fa-pen"></i>
                </button>
                <a href="courts.php?delete=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus lapangan ini? Semua booking terkait juga akan terhapus.')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($courts)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data lapangan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="courtModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Lapangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="f_id">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Lapangan</label>
                <input type="text" name="name" id="f_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" id="f_location" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga per Jam (Rp)</label>
                <input type="number" name="price" id="f_price" class="form-control" required min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" id="f_status" class="form-select">
                    <option value="available">Tersedia</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="f_description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Foto Lapangan <span class="text-muted small">(kosongkan jika tidak ingin ganti)</span></label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            </div>
            <div class="col-12">
                <label class="form-label">Fasilitas</label>
                <div class="row" id="f_facilities">
                    <?php foreach ($allFacilities as $fa): ?>
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input facility-check" type="checkbox" name="facilities[]" value="<?= $fa['id'] ?>" id="fac<?= $fa['id'] ?>">
                            <label class="form-check-label" for="fac<?= $fa['id'] ?>"><?= clean($fa['facility_name']) ?></label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
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
    document.getElementById('f_id').value = '';
    document.getElementById('f_name').value = '';
    document.getElementById('f_location').value = '';
    document.getElementById('f_price').value = '';
    document.getElementById('f_description').value = '';
    document.getElementById('f_status').value = 'available';
    document.querySelectorAll('.facility-check').forEach(c => c.checked = false);
}
function openCreateModal() {
    resetForm();
    document.getElementById('modalTitle').innerText = 'Tambah Lapangan';
}
function openEditModal(data) {
    resetForm();
    document.getElementById('modalTitle').innerText = 'Edit Lapangan';
    document.getElementById('f_id').value = data.id;
    document.getElementById('f_name').value = data.name;
    document.getElementById('f_location').value = data.location;
    document.getElementById('f_price').value = data.price;
    document.getElementById('f_description').value = data.description;
    document.getElementById('f_status').value = data.status;
    data.facilities.forEach(fid => {
        const el = document.getElementById('fac' + fid);
        if (el) el.checked = true;
    });
    new bootstrap.Modal(document.getElementById('courtModal')).show();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
