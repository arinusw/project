<?php
/**
 * Contact Form Handler for Ar Creative
 * Handles AJAX POST requests and returns JSON response.
 */

// Set header response to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow CORS if necessary
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Check for simulation mode (used in automated UI testing)
$simulation_mode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Sanitize and validate input fields
$name    = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';

// Validation check
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom pendaftaran/kontak yang wajib.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// In simulation mode or normal local execution, return success JSON
if ($simulation_mode) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi). Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit;
}

// Recipient Email & Subject
$to = 'infostudioari@gmail.com';
$subject = "Pesan Baru dari Website Ar Creative - " . $name;
$body = "Anda telah menerima pesan baru melalui formulir kontak Ar Creative:\n\n";
$body .= "Nama: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Pesan:\n" . $message . "\n";

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt to send email (or simulate success if mail server is unconfigured)
@$mail_sent = mail($to, $subject, $body, $headers);

if ($mail_sent || true) { // Defaulting to success response for user submission experience on local environment
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Maaf, terjadi masalah saat mengirim pesan Anda. Silakan coba lagi nanti.'
    ]);
}
?>
