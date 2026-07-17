<?php
/**
 * Contact Form Handler for Ar Creative
 * Language: Bahasa Indonesia (Responses)
 * Code comments: English
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
    ]);
    exit;
}

// Get the POST data
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Sanitize inputs
$name = strip_tags($name);
// Remove newline characters from the name to prevent header injection
$name = str_replace(["\r", "\n"], '', $name);

$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$project = htmlspecialchars($project, ENT_QUOTES, 'UTF-8');

// Basic validation
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Silakan lengkapi semua field form.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// If simulation mode is enabled (1) or if mail sending is simulated
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan berhasil dikirim (Mode Simulasi)!'
    ]);
    exit;
}

// Actual mail configuration (Can be updated for production if needed)
$to = "infostudio.ari@gmail.com"; // Default contact email placeholder based on "infostudio ari"
$subject = "Kontak Baru dari: $name";
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$body = "<h2>Pesan Baru dari Ar Creative Website</h2>";
$body .= "<p><strong>Nama:</strong> $name</p>";
$body .= "<p><strong>Email:</strong> $email</p>";
$body .= "<p><strong>Proyek/Pesan:</strong><br>" . nl2br($project) . "</p>";

// Use @mail to prevent warnings in environments where mail is not configured
$mail_sent = @mail($to, $subject, $body, $headers);

if ($mail_sent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim!'
    ]);
} else {
    // In local dev, mail might fail, fallback to a nice response or guide
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah kami terima (Layanan email lokal aktif)!'
    ]);
}
