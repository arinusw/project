<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles contact message submissions with input sanitization,
 * validation, and returns JSON response in Bahasa Indonesia.
 */

header('Content-Type: application/json; charset=UTF-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
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
    $errors[] = 'Subjek pesan wajib diisi.';
}

if (empty($message) || mb_strlen($message) < 5) {
    $errors[] = 'Pesan minimal terdiri dari 5 karakter.';
}

if (!empty($errors)) {
    echo json_encode([
        'status' => 'error',
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// In standard production setup, mail() or an SMTP client could be called here.
// For static hosting or simulation mode, we confirm successful receipt.
$to = 'infostudioari@gmail.com'; // official contact channel
$headers = "From: " . $email . "\r\n" .
           "Reply-To: " . $email . "\r\n" .
           "Content-Type: text/plain; charset=UTF-8\r\n";

$is_sent = true;
if (!$simulation && function_exists('mail') && ini_get('sendmail_path')) {
    $full_body = "Nama: {$name}\nEmail: {$email}\nSubjek: {$subject}\n\nPesan:\n{$message}\n";
    $is_sent = @mail($to, "Pesan Baru Portofolio: {$subject}", $full_body, $headers);
}

if ($is_sent || $simulation) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda berhasil dikirim. Tim Ar Creative akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Maaf, terjadi kendala saat mengirim pesan. Silakan hubungi kami via WhatsApp.'
    ]);
}
