<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/header.php';

$db = Database::connect();
$courts = $db->query("SELECT * FROM courts ORDER BY id ASC LIMIT 4")->fetchAll();
?>

<div class="hero-section text-center">
    <h1 class="fw-bold"><i class="fa-solid fa-table-tennis-paddle-ball"></i> Booking Lapangan Badminton Mudah & Cepat</h1>
    <p class="lead mb-4">Pilih lapangan, pilih jadwal, langsung main. Tanpa ribet, tanpa antre telepon.</p>
    <a href="courts.php" class="btn btn-light btn-lg fw-bold text-success">Lihat Lapangan <i class="fa-solid fa-arrow-right"></i></a>
</div>

<h3 class="fw-bold mb-3">Lapangan Populer</h3>
<div class="row g-4">
    <?php foreach ($courts as $court): ?>
    <div class="col-md-3 col-sm-6">
        <div class="card court-card">
            <img src="assets/uploads/courts/<?= clean($court['image']) ?>"
                 onerror="this.src='https://placehold.co/400x250?text=Lapangan'"
                 alt="<?= clean($court['name']) ?>">
            <div class="card-body">
                <h6 class="fw-bold mb-1"><?= clean($court['name']) ?></h6>
                <p class="text-muted small mb-1"><i class="fa-solid fa-location-dot"></i> <?= clean($court['location']) ?></p>
                <p class="fw-bold text-success mb-2"><?= rupiah($court['price']) ?> <span class="text-muted fw-normal small">/ jam</span></p>
                <a href="court-detail.php?id=<?= (int)$court['id'] ?>" class="btn btn-outline-success btn-sm w-100">Lihat Detail</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($courts)): ?>
        <p class="text-muted">Belum ada lapangan tersedia.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
