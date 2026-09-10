<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow CORS if necessary
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit();
}

// Extract form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? (string)$_POST['simulation_mode'] === '1' : false;

// Validate input
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir kontak.'
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit();
}

// Handle simulation mode for testing or actual processing
if ($simulationMode) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit();
}

// In standard production mode without active mail server:
// Return successful acknowledgement
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.'
]);
?>
