<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Daftar Lapangan';
require_once __DIR__ . '/includes/header.php';

$db = Database::connect();
$courts = $db->query("SELECT * FROM courts ORDER BY id ASC")->fetchAll();
?>

<h3 class="fw-bold mb-4"><i class="fa-solid fa-list"></i> Semua Lapangan</h3>

<div class="row g-4">
    <?php foreach ($courts as $court): ?>
    <div class="col-md-3 col-sm-6">
        <div class="card court-card">
            <img src="assets/uploads/courts/<?= clean($court['image']) ?>"
                 onerror="this.src='https://placehold.co/400x250?text=Lapangan'"
                 alt="<?= clean($court['name']) ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h6 class="fw-bold mb-0"><?= clean($court['name']) ?></h6>
                    <span class="badge <?= $court['status'] === 'available' ? 'badge-status-available' : 'badge-status-maintenance' ?>">
                        <?= $court['status'] === 'available' ? 'Tersedia' : 'Maintenance' ?>
                    </span>
                </div>
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
