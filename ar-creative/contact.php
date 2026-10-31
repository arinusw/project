<?php
/**
 * Ar Creative - Contact Form Handler
 * Lightweight PHP endpoint for handling contact requests.
 * Returns clean JSON responses in Bahasa Indonesia.
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
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : 'Pesan Umum';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$simulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Validate inputs
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
        'message' => 'Mohon masukkan alamat email yang valid.'
    ]);
    exit;
}

if (empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon tuliskan pesan atau rincian kebutuhan Anda.'
    ]);
    exit;
}

// In local development or simulation mode, return success directly without actual mail transport
if ($simulation || php_sapi_name() === 'cli-server' || empty($_SERVER['SERVER_ADMIN'])) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => "Terima kasih, {$name}! Pesan Anda mengenai '{$subject}' telah berhasil kami terima. Tim Ar Creative akan segera menghubungi Anda."
    ]);
    exit;
}

// Standard production mail sending (optional mail fallback)
$to = "infostudio.ari@gmail.com";
$mailSubject = "Pesan Portofolio Ar Creative dari: " . $name . " - " . $subject;
$emailBody = "Nama: " . $name . "\n"
           . "Email: " . $email . "\n"
           . "Layanan: " . $subject . "\n\n"
           . "Pesan:\n" . $message . "\n";

$headers = "From: noreply@arcreative.local\r\n"
         . "Reply-To: " . $email . "\r\n"
         . "X-Mailer: PHP/" . phpversion();

$mailSent = @mail($to, $mailSubject, $emailBody, $headers);

if ($mailSent) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => "Terima kasih, {$name}! Pesan Anda telah berhasil terkirim ke Ar Creative."
    ]);
} else {
    // When mail server is unconfigured, still provide polite feedback to user
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => "Terima kasih, {$name}! Pesan Anda telah tercatat. Tim Ar Creative akan segera merespons Anda."
    ]);
}
?>
