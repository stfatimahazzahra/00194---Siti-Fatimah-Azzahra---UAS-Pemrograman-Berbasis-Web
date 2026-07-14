<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$db = Database::connect();

// ==== UPDATE STATUS / PEMBAYARAN ====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $paymentStatus = $_POST['payment_status'] ?? '';
    $validStatus = ['pending','confirmed','completed','cancelled'];
    $validPayment = ['unpaid','paid'];

    if ($id && in_array($status, $validStatus) && in_array($paymentStatus, $validPayment)) {
        $stmt = $db->prepare("UPDATE bookings SET status=?, payment_status=? WHERE id=?");
        $stmt->execute([$status, $paymentStatus, $id]);
        setFlash('success', 'Status booking berhasil diperbarui.');
    }
    redirect('bookings.php');
}

// ==== DELETE ====
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM bookings WHERE id=?")->execute([(int)$_GET['delete']]);
    setFlash('success', 'Data booking berhasil dihapus.');
    redirect('bookings.php');
}

// ==== FILTER ====
$filterStatus = $_GET['status'] ?? '';
$sql = "SELECT b.*, c.name as court_name FROM bookings b JOIN courts c ON c.id = b.court_id";
$params = [];
if ($filterStatus) {
    $sql .= " WHERE b.status = ?";
    $params[] = $filterStatus;
}
$sql .= " ORDER BY b.booking_date DESC, b.start_time DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();

$statusBadge = [
    'pending' => 'bg-warning text-dark',
    'confirmed' => 'bg-primary',
    'completed' => 'bg-success',
    'cancelled' => 'bg-secondary',
];

$pageTitle = 'Kelola Booking';
require_once __DIR__ . '/includes/header.php';
?>

<div class="mb-3">
    <div class="btn-group">
        <a href="bookings.php" class="btn btn-sm btn-outline-dark <?= $filterStatus==='' ? 'active' : '' ?>">Semua</a>
        <a href="bookings.php?status=pending" class="btn btn-sm btn-outline-warning <?= $filterStatus==='pending' ? 'active' : '' ?>">Pending</a>
        <a href="bookings.php?status=confirmed" class="btn btn-sm btn-outline-primary <?= $filterStatus==='confirmed' ? 'active' : '' ?>">Confirmed</a>
        <a href="bookings.php?status=completed" class="btn btn-sm btn-outline-success <?= $filterStatus==='completed' ? 'active' : '' ?>">Completed</a>
        <a href="bookings.php?status=cancelled" class="btn btn-sm btn-outline-secondary <?= $filterStatus==='cancelled' ? 'active' : '' ?>">Cancelled</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
<div class="table-responsive">
<table class="table align-middle mb-0">
    <thead class="table-light">
        <tr><th>Kode</th><th>Lapangan</th><th>Nama</th><th>Kontak</th><th>Tanggal</th><th>Jam</th><th>Total</th><th>Status</th><th>Bayar</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?= clean($b['booking_code']) ?></td>
            <td><?= clean($b['court_name']) ?></td>
            <td><?= clean($b['guest_name']) ?></td>
            <td><?= clean($b['guest_phone'] ?: '-') ?></td>
            <td><?= clean($b['booking_date']) ?></td>
            <td><?= substr($b['start_time'],0,5) ?>-<?= substr($b['end_time'],0,5) ?></td>
            <td><?= rupiah($b['total_price']) ?></td>
            <td><span class="badge <?= $statusBadge[$b['status']] ?>"><?= ucfirst($b['status']) ?></span></td>
            <td><span class="badge <?= $b['payment_status']==='paid' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($b['payment_status']) ?></span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal"
                    onclick='openEdit(<?= json_encode(["id"=>$b["id"],"status"=>$b["status"],"payment_status"=>$b["payment_status"],"code"=>$b["booking_code"]]) ?>)'>
                    <i class="fa-solid fa-pen"></i>
                </button>
                <a href="bookings.php?delete=<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data booking ini?')">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?>
        <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada data booking.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Update Booking <span id="m_code"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="m_id">
        <div class="mb-3">
            <label class="form-label">Status Booking</label>
            <select name="status" id="m_status" class="form-select">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status Pembayaran</label>
            <select name="payment_status" id="m_payment" class="form-select">
                <option value="unpaid">Unpaid</option>
                <option value="paid">Paid</option>
            </select>
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
function openEdit(data) {
    document.getElementById('m_id').value = data.id;
    document.getElementById('m_status').value = data.status;
    document.getElementById('m_payment').value = data.payment_status;
    document.getElementById('m_code').innerText = data.code;
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
