<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Booking Berhasil';
require_once __DIR__ . '/includes/header.php';

$db = Database::connect();
$code = $_GET['code'] ?? '';
$stmt = $db->prepare("SELECT b.*, c.name as court_name, c.location FROM bookings b
    JOIN courts c ON c.id = b.court_id WHERE b.booking_code = ?");
$stmt->execute([$code]);
$booking = $stmt->fetch();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 text-center p-4">
            <i class="fa-solid fa-circle-check text-success" style="font-size:3rem;"></i>
            <h4 class="fw-bold mt-3">Booking Berhasil Dibuat!</h4>
            <?php if ($booking): ?>
            <p class="text-muted">Simpan kode booking ini sebagai bukti reservasi kamu.</p>
            <h3 class="fw-bold text-success"><?= clean($booking['booking_code']) ?></h3>
            <hr>
            <table class="table table-sm text-start">
                <tr><th>Lapangan</th><td><?= clean($booking['court_name']) ?></td></tr>
                <tr><th>Tanggal</th><td><?= clean($booking['booking_date']) ?></td></tr>
                <tr><th>Jam</th><td><?= substr($booking['start_time'],0,5) ?> - <?= substr($booking['end_time'],0,5) ?></td></tr>
                <tr><th>Total</th><td class="fw-bold"><?= rupiah($booking['total_price']) ?></td></tr>
                <tr><th>Status</th><td><span class="badge bg-warning text-dark">Menunggu Konfirmasi</span></td></tr>
            </table>
            <?php endif; ?>
            <a href="index.php" class="btn btn-success mt-2">Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
