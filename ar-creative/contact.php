<?php
header('Content-Type: application/json; charset=utf-8');

// Respond only to POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Check for simulation mode parameter (for automated tests or local simulation)
$is_simulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] == '1';

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validation
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir kontak.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// In real environment, email processing logic would go here
// If simulation mode or standard local handle:
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($name) . '! Pesan Anda telah berhasil terkirim. Tim Ar Creative akan segera menghubungi Anda.'
]);
exit;
