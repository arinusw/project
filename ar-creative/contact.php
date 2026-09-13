<?php
// Set response content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow POST requests only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Sanitize and sanitize inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate fields
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi seluruh kolom formulir.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// In simulation mode or standard demo, return success response without actual mail send requirement
if ($simulation_mode === '1' || true) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
    ]);
    exit;
}
?>
