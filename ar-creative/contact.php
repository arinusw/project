<?php
/**
 * Ar Creative - Contact Form Handler
 * Lightweight PHP endpoint for handling contact inquiries
 * Returns JSON response
 */

header('Content-Type: application/json; charset=utf-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Retrieve and sanitize inputs
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : 'Pertanyaan Umum';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validation
if (empty($name)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon cantumkan nama lengkap Anda.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($message) || mb_strlen($message) < 5) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon tuliskan pesan atau kebutuhan proyek Anda secara jelas.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Success response
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda berhasil dikirim ke Ar Creative. Kami akan segera menghubungi Anda melalui email atau WhatsApp.'
], JSON_UNESCAPED_UNICODE);
