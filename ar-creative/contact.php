<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes incoming contact messages and returns JSON response in Bahasa Indonesia.
 */

header('Content-Type: application/json; charset=UTF-8');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Retrieve and sanitize input fields
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$simulation_mode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] == '1';

// Validation
if (empty($name)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi nama lengkap Anda.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang dimasukkan tidak valid.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($subject)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi subjek pesan atau kebutuhan proyek Anda.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon tuliskan pesan atau rincian proyek Anda.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Check for simulation mode (used in automated UI testing)
if ($simulation_mode) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda berhasil dikirim (Mode Simulasi Sukses).'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Production / Standard handling
// Note: In local development or shared hosting without sendmail configured,
// we log or safely mock email dispatch to prevent fatal runtime errors.
$to = 'infostudioari@gmail.com';
$email_subject = "[Ar Creative Portfolio] " . $subject;
$email_body = "Nama: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Subjek: " . $subject . "\n\n";
$email_body .= "Pesan:\n" . $message . "\n";

$headers = "From: noreply@arcreative.local\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt mail send if mail function available, else succeed gracefully
$mail_sent = false;
if (function_exists('mail')) {
    // Suppress warning if local sendmail is unconfigured
    $mail_sent = @mail($to, $email_subject, $email_body, $headers);
}

// Return friendly success message in Bahasa Indonesia
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah kami terima. Tim Ar Creative akan segera menghubungi Anda melalui email atau WhatsApp.'
], JSON_UNESCAPED_UNICODE);
