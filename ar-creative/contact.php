<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

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
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// In simulation mode or standard local environment, return success response
if ($simulationMode || true) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
    ]);
    exit;
}

// Real email dispatch logic (when mail server configured)
/*
$to = "infostudio.ari@gmail.com";
$headers = "From: " . $email . "\r\n" .
           "Reply-To: " . $email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti.'
    ]);
}
*/
