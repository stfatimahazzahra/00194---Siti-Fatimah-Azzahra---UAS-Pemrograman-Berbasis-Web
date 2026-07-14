<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';
requireAdmin();
$user = currentUser();
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? clean($pageTitle) . ' - ' : '' ?>Admin RallyPlay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <div class="sidebar p-3" style="width:230px;">
        <h5 class="text-white fw-bold mb-4"><i class="fa-solid fa-shuttlecock"></i> RallyPlay Admin</h5>
        <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a href="courts.php" class="<?= $current === 'courts.php' ? 'active' : '' ?>"><i class="fa-solid fa-table-tennis-paddle-ball"></i> Lapangan</a>
        <a href="bookings.php" class="<?= $current === 'bookings.php' ? 'active' : '' ?>"><i class="fa-regular fa-calendar-check"></i> Booking</a>
        <a href="facilities.php" class="<?= $current === 'facilities.php' ? 'active' : '' ?>"><i class="fa-solid fa-list-check"></i> Fasilitas</a>
        <hr class="text-secondary">
        <a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i> Lihat Situs</a>
        <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><?= isset($pageTitle) ? clean($pageTitle) : 'Dashboard' ?></h4>
            <span class="text-muted">Halo, <b><?= clean($user['name']) ?></b></span>
        </div>
        <?php renderFlash(); ?>
