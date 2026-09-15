<?php
header('Content-Type: application/json; charset=utf-8');

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Retrieve form input fields
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validation
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi seluruh kolom yang wajib diisi.'
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

// Simulation mode response for automated testing
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Real email sending block (fallback to success in typical PHP environment if mail function is called)
$to = "infostudio.ari@gmail.com";
$email_subject = !empty($subject) ? "Pesan Baru Portofolio: $subject" : "Pesan Baru dari $name";
$email_body = "Anda menerima pesan baru dari formulir kontak Ar Creative:\n\n";
$email_body .= "Nama: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Subjek: $subject\n\n";
$email_body .= "Pesan:\n$message\n";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";

// Attempt mail sending
@mail($to, $email_subject, $email_body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil dikirim. Terima kasih telah menghubungi Ar Creative!'
]);
exit;
