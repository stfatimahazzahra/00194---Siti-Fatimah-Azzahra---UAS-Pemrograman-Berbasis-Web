<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('courts.php');
}

$db = Database::connect();
$user = currentUser();

$courtId = (int)($_POST['court_id'] ?? 0);
$bookingDate = $_POST['booking_date'] ?? '';
$startTime = $_POST['start_time'] ?? '';
$duration = (float)($_POST['duration'] ?? 0);
$guestName = clean($_POST['guest_name'] ?? '');
$guestPhone = clean($_POST['guest_phone'] ?? '');

if (!$courtId || !$bookingDate || !$startTime || !$duration) {
    setFlash('error', 'Data booking tidak lengkap.');
    redirect('court-detail.php?id=' . $courtId);
}

if (!$user && (empty($guestName) || empty($guestPhone))) {
    setFlash('error', 'Nama dan No. HP wajib diisi untuk booking sebagai tamu.');
    redirect('court-detail.php?id=' . $courtId);
}

// Ambil data lapangan
$stmt = $db->prepare("SELECT * FROM courts WHERE id = ?");
$stmt->execute([$courtId]);
$court = $stmt->fetch();

if (!$court || $court['status'] !== 'available') {
    setFlash('error', 'Lapangan tidak tersedia untuk dibooking.');
    redirect('courts.php');
}

// Hitung end_time
$startDT = DateTime::createFromFormat('H:i:s', $startTime);
$endDT = clone $startDT;
$endDT->modify('+' . ($duration * 60) . ' minutes');
$endTime = $endDT->format('H:i:s');

// Cek bentrok terhadap bookings aktif & schedules pada tanggal & lapangan yang sama
$stmt = $db->prepare("SELECT COUNT(*) as total FROM bookings
    WHERE court_id = ? AND booking_date = ? AND status != 'cancelled'
    AND start_time < ? AND end_time > ?");
$stmt->execute([$courtId, $bookingDate, $endTime, $startTime]);
$conflictBooking = $stmt->fetch()['total'];

$stmt = $db->prepare("SELECT COUNT(*) as total FROM schedules
    WHERE court_id = ? AND date = ?
    AND start_time < ? AND end_time > ?");
$stmt->execute([$courtId, $bookingDate, $endTime, $startTime]);
$conflictSchedule = $stmt->fetch()['total'];

if ($conflictBooking > 0 || $conflictSchedule > 0) {
    setFlash('error', 'Maaf, jam yang dipilih sudah terisi. Silakan pilih jam lain.');
    redirect('court-detail.php?id=' . $courtId);
}

$totalPrice = $court['price'] * $duration;
$bookingCode = generateBookingCode();

$stmt = $db->prepare("INSERT INTO bookings
    (booking_code, user_id, guest_name, guest_phone, court_id, booking_date, start_time, end_time, duration, total_price, status, payment_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'unpaid')");
$stmt->execute([
    $bookingCode,
    $user['id'] ?? null,
    $user ? $user['name'] : $guestName,
    $user ? null : $guestPhone,
    $courtId,
    $bookingDate,
    $startTime,
    $endTime,
    $duration,
    $totalPrice,
]);

setFlash('success', 'Booking berhasil dibuat! Kode booking kamu: ' . $bookingCode . '. Status menunggu konfirmasi admin.');

if ($user) {
    redirect('dashboard.php');
} else {
    redirect('booking-success.php?code=' . urlencode($bookingCode));
}
