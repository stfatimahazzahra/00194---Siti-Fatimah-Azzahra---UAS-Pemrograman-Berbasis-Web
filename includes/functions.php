<?php
// includes/functions.php

function clean($str) {
    return htmlspecialchars(trim($str ?? ''), ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function renderFlash() {
    $flash = getFlash();
    if (!$flash) return;
    $type = $flash['type'] === 'error' ? 'danger' : $flash['type'];
    echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">'
        . clean($flash['message'])
        . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

function generateBookingCode() {
    return 'RP-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}
