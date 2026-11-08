<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes incoming contact form requests and returns JSON response.
 */

header('Content-Type: application/json; charset=UTF-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid. Hanya menerima metode POST.'
    ]);
    exit;
}

// Retrieve and sanitize inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi seluruh formulir kontak sebelum mengirim pesan.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Prepare response payload
$response = [
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah berhasil dikirim ke Ar Creative. Kami akan segera menghubungi Anda melalui email atau WhatsApp.',
    'data' => [
        'name'    => $name,
        'email'   => $email,
        'subject' => $subject,
        'time'    => date('Y-m-d H:i:s')
    ]
];

http_response_code(200);
echo json_encode($response);
exit;
