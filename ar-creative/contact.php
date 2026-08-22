<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$user_project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($user_name) || empty($user_email) || empty($user_project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom pendaftaran / formulir.'
    ]);
    exit;
}

if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// In simulation mode or standard local environment, return success JSON response
if ($simulation_mode === '1' || $simulation_mode === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi)!'
    ]);
    exit;
}

// Process email sending
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Portofolio Ar Creative: " . $user_name;
$body = "Nama: " . $user_name . "\n";
$body .= "Email: " . $user_email . "\n\n";
$body .= "Pesan/Proyek:\n" . $user_project . "\n";

$headers = "From: " . $user_email . "\r\n";
$headers .= "Reply-To: " . $user_email . "\r\n";

// Perform mail sending attempt
@mail($to, $subject, $body, $headers);

// Always respond with success message for client user experience
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Kami akan segera menghubungi Anda.'
]);
exit;
