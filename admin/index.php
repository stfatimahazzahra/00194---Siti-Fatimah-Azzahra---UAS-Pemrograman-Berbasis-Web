<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

$db = Database::connect();

$totalCourts = $db->query("SELECT COUNT(*) c FROM courts")->fetch()['c'];
$totalBookings = $db->query("SELECT COUNT(*) c FROM bookings")->fetch()['c'];
$pendingBookings = $db->query("SELECT COUNT(*) c FROM bookings WHERE status='pending'")->fetch()['c'];
$revenue = $db->query("SELECT COALESCE(SUM(total_price),0) r FROM bookings WHERE payment_status='paid'")->fetch()['r'];

$recent = $db->query("SELECT b.*, c.name as court_name FROM bookings b
    JOIN courts c ON c.id = b.court_id ORDER BY b.created_at DESC LIMIT 8")->fetchAll();

$statusBadge = [
    'pending' => 'bg-warning text-dark',
    'confirmed' => 'bg-primary',
    'completed' => 'bg-success',
    'cancelled' => 'bg-secondary',
];
?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="background:#198754;">
            <div class="small">Total Lapangan</div>
            <div class="fs-3 fw-bold"><?= $totalCourts ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#0d6efd;">
            <div class="small">Total Booking</div>
            <div class="fs-3 fw-bold"><?= $totalBookings ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#fd7e14;">
            <div class="small">Menunggu Konfirmasi</div>
            <div class="fs-3 fw-bold"><?= $pendingBookings ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#20c997;">
            <div class="small">Pendapatan (Lunas)</div>
            <div class="fs-5 fw-bold"><?= rupiah($revenue) ?></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Booking Terbaru</h6>
        <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead class="table-light">
                <tr><th>Kode</th><th>Lapangan</th><th>Nama</th><th>Tanggal</th><th>Jam</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $b): ?>
                <tr>
                    <td><?= clean($b['booking_code']) ?></td>
                    <td><?= clean($b['court_name']) ?></td>
                    <td><?= clean($b['guest_name']) ?></td>
                    <td><?= clean($b['booking_date']) ?></td>
                    <td><?= substr($b['start_time'],0,5) ?>-<?= substr($b['end_time'],0,5) ?></td>
                    <td><span class="badge <?= $statusBadge[$b['status']] ?>"><?= ucfirst($b['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recent)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada booking.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
