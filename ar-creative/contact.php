<?php
/**
 * Ar Creative - Simple Contact Form Handler
 * Returns JSON response
 */

header('Content-Type: application/json; charset=utf-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan. Gunakan metode POST.'
    ]);
    exit;
}

// Sanitize and validate inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

if (empty($name)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi nama lengkap Anda.'
    ]);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang dimasukkan tidak valid.'
    ]);
    exit;
}

if (empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Pesan tidak boleh kosong.'
    ]);
    exit;
}

// In real environment, you can send mail or save to database/log
// Here we log locally and return a successful JSON response
$logEntry = sprintf(
    "[%s] Kontak Masuk - Nama: %s | Email: %s | Topik: %s | Pesan: %s\n",
    date('Y-m-d H:i:s'),
    $name,
    $email,
    $subject ?: 'Umum',
    str_replace(["\r", "\n"], ' ', $message)
);

// Optional: log to messages.log if writable
@file_put_contents(__DIR__ . '/messages.log', $logEntry, FILE_APPEND | LOCK_EX);

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah berhasil dikirim. Tim Ar Creative akan segera menghubungi Anda.'
]);
exit;
