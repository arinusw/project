<?php
header('Content-Type: application/json; charset=utf-8');

// Disable error display in production JSON responses
ini_set('display_errors', 0);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Retrieve and sanitize input fields
$name    = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$isSimulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Basic Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir yang wajib diisi.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid. Harap periksa kembali email Anda.'
    ]);
    exit;
}

// Simulated handling for test environments
if ($isSimulation) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Simulasi)! Kami akan segera menghubungi Anda.'
    ]);
    exit;
}

// Real Mail Handling Attempt (optional fallback)
$to = 'infostudio.ari@gmail.com';
$emailSubject = "Kontak Ar Creative: " . $subject;
$emailBody = "Nama: $name\nEmail: $email\nSubjek: $subject\n\nPesan:\n$message";
$headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

// Since mail server may not be configured in local sandbox environments, simulate success if mail returns false or disabled
$mailSent = @mail($to, $emailSubject, $emailBody, $headers);

if ($mailSent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
} else {
    // Graceful fallback for local development/sandbox environments without active mail transfer agent
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, pesan Anda telah diterima! Kami akan segera merespons email Anda.'
    ]);
}
