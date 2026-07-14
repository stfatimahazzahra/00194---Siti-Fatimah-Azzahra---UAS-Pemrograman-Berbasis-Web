<?php
require_once __DIR__ . '/config/database.php';
header('Content-Type: application/json');

$courtId = (int)($_GET['court_id'] ?? 0);
$date = $_GET['date'] ?? '';

if (!$courtId || !$date) {
    echo json_encode(['success' => false, 'message' => 'Parameter tidak lengkap']);
    exit;
}

$db = Database::connect();

$stmt = $db->prepare("SELECT start_time, end_time, 'booking' as source FROM bookings
    WHERE court_id = ? AND booking_date = ? AND status != 'cancelled'
    UNION ALL
    SELECT start_time, end_time, 'schedule' as source FROM schedules
    WHERE court_id = ? AND date = ?");
$stmt->execute([$courtId, $date, $courtId, $date]);
$slots = $stmt->fetchAll();

echo json_encode(['success' => true, 'slots' => $slots]);
