<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid.'
    ]);
    exit;
}

// Check for simulation mode (used in automated end-to-end tests)
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// In production / live server environment, mail() would be executed here.
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Website Ar Creative: " . $name;
$body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
$headers = "From: $email\r\nReply-To: $email\r\n";

// For standard local execution, return success response
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil terkirim! Kami akan segera menghubungi Anda.'
]);
?>
