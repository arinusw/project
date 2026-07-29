<?php
/**
 * Ar Creative - Contact Form Handler
 * Language: Bahasa Indonesia
 *
 * Handles POST request from contact form, validates user input,
 * and responds with JSON status and message.
 */

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit;
}

// Get and sanitize POST inputs
$name = isset($_POST['user_name']) ? trim(htmlspecialchars($_POST['user_name'])) : '';
$email = isset($_POST['user_email']) ? trim(filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL)) : '';
$project = isset($_POST['user_project']) ? trim(htmlspecialchars($_POST['user_project'])) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Validation
if (empty($name) || empty($email) || empty($project)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi semua field (Nama, Email, dan Pesan/Proyek) sebelum mengirim.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid. Silakan periksa kembali email Anda.'
    ]);
    exit;
}

// If in simulation mode, return success immediately
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Simulasi sukses! Pesan Anda berhasil dikirim (Mode Simulasi).'
    ]);
    exit;
}

// In production / non-simulation mode, try sending an actual email
$to = 'infostudio.ari@gmail.com'; // Ar Creative email
$subject = "Pesan Baru dari Hubungi Kami - $name";
$email_content = "Nama: $name\n";
$email_content .= "Email: $email\n\n";
$email_content .= "Pesan/Proyek:\n$project\n";

$headers = "From: $name <$email>\r\nReply-To: $email\r\n";

if (@mail($to, $subject, $email_content, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda kembali.'
    ]);
} else {
    // If mail sending fails in standard environment, let user know or provide fallback
    // Since some local environments don't have mail server configured, we can still fall back
    // or let them know they can contact us via WhatsApp.
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengirim pesan melalui server email. Silakan coba hubungi kami langsung via WhatsApp.'
    ]);
}
