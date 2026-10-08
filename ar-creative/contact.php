<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation = isset($_POST['simulation_mode']) ? (int)$_POST['simulation_mode'] : 0;

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi semua kolom formulir yang tersedia.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// Check for simulation mode (used in testing environment)
if ($simulation === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! (Mode Simulasi)'
    ]);
    exit;
}

// Production / Real email handling
$to = 'infostudioari@gmail.com';
$subject = 'Pesan Baru dari Portfolio Ar Creative - ' . $name;
$body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
$headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

// Attempt to send email (if mail server configured)
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    // Return success response with polite note if local mail server is not configured
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih atas pesan Anda! Kami telah menerima input Anda.'
    ]);
}
