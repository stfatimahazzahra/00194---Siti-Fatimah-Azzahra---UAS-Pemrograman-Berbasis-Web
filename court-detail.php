<?php
require_once __DIR__ . '/config/database.php';
$db = Database::connect();

$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM courts WHERE id = ?");
$stmt->execute([$id]);
$court = $stmt->fetch();

if (!$court) {
    $pageTitle = 'Tidak Ditemukan';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-warning">Lapangan tidak ditemukan.</div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = $db->prepare("SELECT f.facility_name, f.icon FROM court_facilities cf
    JOIN facilities f ON f.id = cf.facility_id WHERE cf.court_id = ?");
$stmt->execute([$id]);
$facilities = $stmt->fetchAll();

$stmt = $db->prepare("SELECT * FROM gallery WHERE court_id = ?");
$stmt->execute([$id]);
$gallery = $stmt->fetchAll();

$pageTitle = $court['name'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="row g-4">
    <div class="col-lg-7">
        <img src="assets/uploads/courts/<?= clean($court['image']) ?>"
             onerror="this.src='https://placehold.co/800x450?text=Lapangan'"
             class="img-fluid rounded-4 shadow-sm mb-3" alt="<?= clean($court['name']) ?>">

        <?php if (!empty($gallery)): ?>
        <div class="row g-2 mb-4">
            <?php foreach ($gallery as $g): ?>
            <div class="col-3">
                <img src="assets/uploads/courts/<?= clean($g['image']) ?>"
                     onerror="this.src='https://placehold.co/200x150?text=Foto'"
                     class="img-fluid rounded-3" style="height:80px;object-fit:cover;width:100%">
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h3 class="fw-bold"><?= clean($court['name']) ?></h3>
        <p class="text-muted"><i class="fa-solid fa-location-dot"></i> <?= clean($court['location']) ?></p>
        <p class="fs-5 fw-bold text-success"><?= rupiah($court['price']) ?> <span class="text-muted fw-normal fs-6">/ jam</span></p>
        <p><?= nl2br(clean($court['description'])) ?></p>

        <h5 class="fw-bold mt-4">Fasilitas</h5>
        <div class="row">
            <?php foreach ($facilities as $f): ?>
            <div class="col-6 col-md-4 mb-2">
                <i class="fa-solid <?= clean($f['icon']) ?> text-success"></i> <?= clean($f['facility_name']) ?>
            </div>
            <?php endforeach; ?>
            <?php if (empty($facilities)): ?><p class="text-muted">Belum ada data fasilitas.</p><?php endif; ?>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="fa-regular fa-calendar-check"></i> Booking Lapangan Ini</h5>

                <?php if ($court['status'] !== 'available'): ?>
                    <div class="alert alert-warning">Lapangan sedang dalam maintenance, tidak bisa dibooking saat ini.</div>
                <?php else: ?>

                <form action="booking-process.php" method="POST" id="bookingForm">
                    <input type="hidden" name="court_id" value="<?= (int)$court['id'] ?>">

                    <?php if (!isLoggedIn()): ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="guest_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="guest_phone" class="form-control" required>
                    </div>
                    <p class="small text-muted">Sudah punya akun? <a href="login.php">Login</a> supaya booking tersimpan di riwayat kamu.</p>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="booking_date" id="booking_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <select name="start_time" id="start_time" class="form-select" required>
                                <?php for ($h = 6; $h <= 21; $h++): ?>
                                    <option value="<?= sprintf('%02d:00:00', $h) ?>"><?= sprintf('%02d:00', $h) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Durasi (jam)</label>
                            <select name="duration" id="duration" class="form-select" required>
                                <option value="1">1 jam</option>
                                <option value="1.5">1.5 jam</option>
                                <option value="2">2 jam</option>
                                <option value="3">3 jam</option>
                            </select>
                        </div>
                    </div>

                    <div id="slotInfo" class="small text-muted mb-3"></div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">Total Harga</span>
                        <span class="fw-bold fs-5 text-success" id="totalPrice"><?= rupiah($court['price']) ?></span>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">Booking Sekarang</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
const courtPrice = <?= (float)$court['price'] ?>;
const courtId = <?= (int)$court['id'] ?>;

function updatePrice() {
    const duration = parseFloat(document.getElementById('duration').value);
    const total = courtPrice * duration;
    document.getElementById('totalPrice').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
document.getElementById('duration').addEventListener('change', updatePrice);

async function checkSlots() {
    const date = document.getElementById('booking_date').value;
    const info = document.getElementById('slotInfo');
    if (!date) { info.innerText = ''; return; }
    info.innerText = 'Mengecek jadwal...';
    try {
        const res = await fetch(`get-slots.php?court_id=${courtId}&date=${date}`);
        const data = await res.json();
        if (data.success && data.slots.length > 0) {
            const list = data.slots.map(s => s.start_time.substring(0,5) + '-' + s.end_time.substring(0,5)).join(', ');
            info.innerHTML = '<i class="fa-solid fa-circle-exclamation text-warning"></i> Jam terisi pada tanggal ini: ' + list;
        } else {
            info.innerHTML = '<i class="fa-solid fa-circle-check text-success"></i> Semua jam tersedia pada tanggal ini.';
        }
    } catch (e) {
        info.innerText = '';
    }
}
document.getElementById('booking_date').addEventListener('change', checkSlots);
updatePrice();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
