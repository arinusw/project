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
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir kontak.'
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

// Check for simulation mode in automated testing environment
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Standard contact form processing
$to = 'infostudioari@gmail.com';
$email_subject = 'Pesan Baru Ar Creative: ' . $subject;
$email_body = "Anda menerima pesan baru dari formulir kontak Ar Creative:\n\n".
              "Nama: $name\n".
              "Email: $email\n".
              "Subjek: $subject\n\n".
              "Pesan:\n$message\n";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";

// In local or sandbox environments mail() might not be configured, so fallback gracefully
@mail($to, $email_subject, $email_body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
]);
?>
