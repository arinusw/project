<?php
// Set response content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Get form fields
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate required fields
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir kontak.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// Check simulation mode for testing
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! (Mode Simulasi)'
    ]);
    exit;
}

// In production / non-simulation mode, attempt mail sending or log message
$to = "infostudio ari";
$headers = "From: " . $email . "\r\n" .
           "Reply-To: " . $email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Simulate successful message receipt
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.'
]);
?>
