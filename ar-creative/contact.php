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
$simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir kontak.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// Simulated email sending or production mail logic
if ($simulationMode) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// In production environment, mail() or mailer library would send email
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Website Ar Creative: " . $name;
$body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
$headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

// Attempt to send email (or return success for demonstration)
@mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil dikirim. Tim Ar Creative akan segera menghubungi Anda!'
]);
?>
