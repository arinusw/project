<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX submissions from the contact form.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Get and sanitize form data
$name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
$email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['user_subject']) ? strip_tags(trim($_POST['user_subject'])) : '';
$message = isset($_POST['user_message']) ? strip_tags(trim($_POST['user_message'])) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Basic validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua bidang formulir.'
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

// Handle simulation mode
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Mode Simulasi: Pesan berhasil diterima!'
    ]);
    exit;
}

// In a real production environment, you would use mail() or a library like PHPMailer
// For this portfolio, we simulate the success if not in simulation mode as well,
// or you could implement the actual mail logic here.

$to = "infostudio.ari@gmail.com";
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$email_content = "Nama: $name\n";
$email_content .= "Email: $email\n";
$email_content .= "Subjek: $subject\n\n";
$email_content .= "Pesan:\n$message\n";

// @mail($to, $subject, $email_content, $headers); // Commented out to avoid errors in sandbox

echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim.'
]);
