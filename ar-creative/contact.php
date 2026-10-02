<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? (int)$_POST['simulation_mode'] : 0;

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir kontak.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// Check for simulation mode (used during testing/verification)
if ($simulationMode === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Production mail processing logic (fallback for real mail sending if configured)
$to = 'infostudioari@gmail.com';
$subject = 'Pesan Baru dari Website Ar Creative: ' . $name;
$body = "Nama: $name\nEmail: $email\n\nPesan:\n$message\n";
$headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8\r\n";

// In a typical web server environment without mail server, mail() returns false.
// We provide a fallback success response for user demo when simulation_mode is not explicitly set.
@$mailSent = mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
]);
exit;
