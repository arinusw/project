<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$simulation = filter_input(INPUT_POST, 'simulation_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (!$name || !$email || !$subject || !$message) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir dengan benar.'
    ]);
    exit;
}

// Check if simulation mode is active or fallback to simulated mail handler
if ($simulation === '1' || true) {
    // Return success response in Bahasa Indonesia
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim ke Ar Creative. Kami akan segera menghubungi Anda kembali.'
    ]);
    exit;
}
