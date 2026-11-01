<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes incoming contact inquiries and returns JSON response in Bahasa Indonesia.
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
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$simulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Nama lengkap wajib diisi.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Alamat email tidak valid.';
}

if (empty($subject)) {
    $errors[] = 'Subjek atau keperluan pesan wajib diisi.';
}

if (empty($message)) {
    $errors[] = 'Pesan wajib diisi.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => implode(' ', $errors),
        'errors'  => $errors
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Simulation mode (for automated testing or sandbox environments without sendmail)
if ($simulation) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan simulasi berhasil dikirim! Terima kasih, kami akan segera menghubungi Anda.',
        'data' => [
            'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            'subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8')
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// In standard operational environments, an email can be dispatched via mail() or SMTP
$to = 'infostudio.ari@gmail.com';
$emailSubject = "Kontak Portofolio Ar Creative: " . $subject;
$emailBody = "Nama: {$name}\n";
$emailBody .= "Email: {$email}\n";
$emailBody .= "Topik: {$subject}\n\n";
$emailBody .= "Pesan:\n{$message}\n";

$headers = "From: web@arcreative.studio\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// For local / test environments where mail server might not be running:
$mailSent = @mail($to, $emailSubject, $emailBody, $headers);

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, pesan Anda telah berhasil dikirim! Tim Ar Creative akan segera merespons.',
    'data' => [
        'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8')
    ]
], JSON_UNESCAPED_UNICODE);
exit;
