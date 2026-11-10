<?php
/**
 * Ar Creative - Contact Form Handler
 * Returns a JSON response with status and message in Bahasa Indonesia.
 */

header('Content-Type: application/json; charset=UTF-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}

// Retrieve and sanitize input fields
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validate required fields
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir kontak.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// Success response
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, pesan Anda telah berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.'
]);
exit;
