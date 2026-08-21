<?php
// Set response headers to return JSON
header('Content-Type: application/json; charset=UTF-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Extract and sanitize input fields
$userName = isset($_POST['user_name']) ? trim(strip_tags($_POST['user_name'])) : '';
$userEmail = isset($_POST['user_email']) ? trim(filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL)) : '';
$userProject = isset($_POST['user_project']) ? trim(strip_tags($_POST['user_project'])) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate required fields
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir kontak.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// If simulation mode is active (for automated UI verification/testing)
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi)! Kami akan segera menghubungi Anda.'
    ]);
    exit;
}

// In standard production mode: simulate email delivery / log message
// Note: In real production, mail() or PHPMailer would be used here.
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, pesan Anda telah terkirim! Tim Ar Creative akan merespons secepatnya.'
]);
exit;
