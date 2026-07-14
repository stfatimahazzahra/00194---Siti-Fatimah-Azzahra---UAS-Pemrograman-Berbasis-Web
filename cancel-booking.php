<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}

$db = Database::connect();
$user = currentUser();
$bookingId = (int)($_POST['booking_id'] ?? 0);

// Pastikan booking ini milik user yang login & masih pending
$stmt = $db->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
$stmt->execute([$bookingId, $user['id']]);
$booking = $stmt->fetch();

if (!$booking) {
    setFlash('error', 'Booking tidak ditemukan.');
    redirect('dashboard.php');
}
if ($booking['status'] !== 'pending') {
    setFlash('error', 'Booking ini tidak bisa dibatalkan.');
    redirect('dashboard.php');
}

$stmt = $db->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?");
$stmt->execute([$bookingId]);

setFlash('success', 'Booking berhasil dibatalkan.');
redirect('dashboard.php');
