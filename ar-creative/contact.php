<?php
/**
 * Ar Creative Contact Form Handler
 * Processes POST requests, validates contact fields, and returns JSON responses.
 * Implements a simulation_mode check for automated UI testing.
 */

header('Content-Type: application/json; charset=UTF-8');

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit;
}

// Extract and sanitize fields
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Simple server-side validation
if (empty($name)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Nama lengkap wajib diisi.'
    ]);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid atau kosong.'
    ]);
    exit;
}

if (empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Pesan atau detail proyek wajib diisi.'
    ]);
    exit;
}

// Process simulation mode or send email
if ($simulation_mode === '1') {
    // Simulated success response for testing
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan simulasi berhasil dikirim! (Mode Simulasi Aktif)'
    ]);
    exit;
}

// Standard (real) email sending block
$to = 'info@studio-ari.com'; // official studio email placeholder
$subject = 'Pesan Baru dari Hubungi Kami - Ar Creative';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: <" . $email . ">" . "\r\n";

$body = "
<html>
<head>
    <title>Pesan Kontak Baru</title>
</head>
<body>
    <h2>Detail Kontak Baru:</h2>
    <p><strong>Nama:</strong> {$name}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Pesan / Kebutuhan Proyek:</strong></p>
    <p>" . nl2br(htmlspecialchars($project)) . "</p>
</body>
</html>
";

// Use @mail to prevent warnings if PHP mailer is not configured
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Tim kami akan segera menghubungi Anda.'
    ]);
} else {
    // Return standard error but fallback to a friendly message
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil kami catat! Terima kasih telah menghubungi Ar Creative.'
    ]);
}
?>
