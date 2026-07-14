<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$db = Database::connect();
$user = currentUser();

$stmt = $db->prepare("SELECT b.*, c.name as court_name, c.location FROM bookings b
    JOIN courts c ON c.id = b.court_id
    WHERE b.user_id = ? ORDER BY b.booking_date DESC, b.start_time DESC");
$stmt->execute([$user['id']]);
$bookings = $stmt->fetchAll();

$statusBadge = [
    'pending' => 'bg-warning text-dark',
    'confirmed' => 'bg-primary',
    'completed' => 'bg-success',
    'cancelled' => 'bg-secondary',
];

$pageTitle = 'Booking Saya';
require_once __DIR__ . '/includes/header.php';
?>

<h3 class="fw-bold mb-4"><i class="fa-regular fa-calendar-check"></i> Riwayat Booking Saya</h3>

<div class="table-responsive">
<table class="table table-bordered bg-white align-middle">
    <thead class="table-light">
        <tr>
            <th>Kode</th><th>Lapangan</th><th>Tanggal</th><th>Jam</th><th>Total</th><th>Status</th><th>Pembayaran</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?= clean($b['booking_code']) ?></td>
            <td><?= clean($b['court_name']) ?><br><small class="text-muted"><?= clean($b['location']) ?></small></td>
            <td><?= clean($b['booking_date']) ?></td>
            <td><?= substr($b['start_time'],0,5) ?> - <?= substr($b['end_time'],0,5) ?></td>
            <td><?= rupiah($b['total_price']) ?></td>
            <td><span class="badge <?= $statusBadge[$b['status']] ?>"><?= ucfirst($b['status']) ?></span></td>
            <td><span class="badge <?= $b['payment_status'] === 'paid' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($b['payment_status']) ?></span></td>
            <td>
                <?php if ($b['status'] === 'pending'): ?>
                <form action="cancel-booking.php" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                    <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
                </form>
                <?php else: ?>
                    <span class="text-muted small">-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">Kamu belum pernah booking lapangan. <a href="courts.php">Booking sekarang</a>.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
