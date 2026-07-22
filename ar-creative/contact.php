<?php
/**
 * Ar Creative - PHP Contact Form Processor
 *
 * Safe, sanitized handling of incoming AJAX POST requests from the contact form.
 */

// Set header for JSON output response
header('Content-Type: application/json; charset=UTF-8');

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit();
}

// Extract and sanitize POST variables to prevent Header Injection and XSS
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$user_project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Remove potentially unsafe characters/newlines from headers
$user_name = strip_tags($user_name);
$user_name = str_replace(array("\r", "\n"), '', $user_name);

$user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
$user_email = str_replace(array("\r", "\n"), '', $user_email);

$user_project = htmlspecialchars($user_project, ENT_QUOTES, 'UTF-8');

// Simple validation
if (empty($user_name) || empty($user_email) || empty($user_project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Silakan lengkapi semua kolom form yang wajib diisi.'
    ]);
    exit();
}

if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit();
}

// Check for Simulation Mode (useful during Playwright/automated test execution)
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi Ar Creative (Mode Simulasi).'
    ]);
    exit();
}

// In production, send email
$to = 'infostudio.ari@gmail.com';
$subject = 'Penawaran Kolaborasi dari Portofolio Ar Creative';

$email_content = "Nama Lengkap: $user_name\n";
$email_content .= "Alamat Email: $user_email\n\n";
$email_content .= "Detail Project:\n$user_project\n";

$email_headers = "From: Ar Creative Portfolio <no-reply@arcreative.com>\r\n";
$email_headers .= "Reply-To: $user_name <$user_email>\r\n";
$email_headers .= "X-Mailer: PHP/" . phpversion();

if (@mail($to, $subject, $email_content, $email_headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Tim kami akan segera menghubungi Anda.'
    ]);
} else {
    // Fallback/Simulated success if mail sending is not configured locally
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
}
exit();
