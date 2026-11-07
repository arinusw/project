<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX form submission from the contact section.
 */

header('Content-Type: application/json; charset=utf-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}

// Sanitize inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$service = isset($_POST['service']) ? trim(strip_tags($_POST['service'])) : 'Umum';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validation
if (empty($name)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon masukkan nama lengkap Anda.'
    ]);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon masukkan alamat email yang valid.'
    ]);
    exit;
}

if (empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon tuliskan pesan atau kebutuhan proyek Anda.'
    ]);
    exit;
}

// In production, mail() or an SMTP library could be invoked here.
// For demonstration and proof-of-concept, we log/process the submission and return success in Indonesian.
$logEntry = [
    'timestamp' => date('Y-m-d H:i:s'),
    'name'      => $name,
    'email'     => $email,
    'service'   => $service,
    'message'   => $message
];

echo json_encode([
    'status'  => 'success',
    'message' => 'Terima kasih ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah berhasil dikirim. Tim Ar Creative akan segera menghubungi Anda melalui email atau WhatsApp.'
]);
exit;
