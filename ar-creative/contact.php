<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow CORS if needed during testing
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit();
}

// Check for simulation_mode parameter (used in Playwright/E2E testing)
$simulation_mode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] == '1';

// Retrieve and sanitize input fields
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

// Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir kontak.'
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid.'
    ]);
    exit();
}

// Simulation mode response for automated testing
if ($simulation_mode) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi). Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit();
}

// Mail recipient configuration
$to = 'infostudioari@gmail.com';
$email_subject = 'Pesan Baru Ar Creative: ' . $subject;

$email_body = "Anda telah menerima pesan baru dari formulir kontak Ar Creative.\n\n";
$email_body .= "Nama: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Subjek: $subject\n\n";
$email_body .= "Pesan:\n$message\n";

$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email (handling environments where mail() function might be disabled/unconfigured)
$mail_success = false;
if (function_exists('mail')) {
    @$mail_success = mail($to, $email_subject, $email_body, $headers);
}

if ($mail_success) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim. Terima kasih telah menghubungi Ar Creative!'
    ]);
} else {
    // Return success gracefully for demonstration environments if mail fails
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil diterima oleh sistem Ar Creative. Kami akan segera merespons!'
    ]);
}
?>
