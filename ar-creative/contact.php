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
$simulation = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi semua kolom formulir dengan benar.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Check if simulation mode is active or normal processing
if ($simulation === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan simulasi Anda berhasil diterima! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// In production / normal server, message logging or mail sending logic can be placed here
$logEntry = sprintf("[%s] Name: %s | Email: %s | Message: %s\n", date('Y-m-d H:i:s'), $name, $email, $message);
@file_put_contents(__DIR__ . '/messages.log', $logEntry, FILE_APPEND);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Kami akan segera menghubungi Anda kembali.'
]);
?>
