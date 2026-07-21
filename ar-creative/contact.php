<?php
/**
 * Ar Creative - PHP Contact Form Handler
 * Handles the async post request from the frontend form.
 */

header('Content-Type: application/json');

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Extract variables
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$user_project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Sanitization & validation
$user_name = strip_tags($user_name);
// Specifically remove newline characters to prevent mail header injection
$user_name = str_replace(array("\r", "\n"), '', $user_name);

$user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
$user_project = strip_tags($user_project);

if (empty($user_name) || empty($user_email) || empty($user_project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua field yang diperlukan.'
    ]);
    exit;
}

if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// If simulation_mode is enabled (1), return success immediately without trying to send mail
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi)!'
    ]);
    exit;
}

// In production, we'd normally execute standard PHP mail()
// Receiver Email
$to = "infostudio.ari@gmail.com";
$subject = "Pesan Baru dari Portofolio Ar Creative - " . $user_name;

// Email Content
$email_content = "Nama: $user_name\n";
$email_content .= "Email: $user_email\n\n";
$email_content .= "Pesan:\n$user_project\n";

// Email Headers
$headers = "From: $user_name <$user_email>\r\n";
$headers .= "Reply-To: $user_email\r\n";

// Attempt to send email
if (mail($to, $subject, $email_content, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengirim pesan melalui mail server. Silakan coba WhatsApp kami.'
    ]);
}
?>
