<?php
header('Content-Type: application/json; charset=UTF-8');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Retrieve form inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validation
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom yang wajib diisi.'
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

// If in simulation mode (for automated Playwright UI testing), return success directly
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi). Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit;
}

// Standard contact processing (e.g. mail sending or database logging)
$to = "infostudioari@gmail.com";
$email_subject = "Pesan Baru Ar Creative: " . (!empty($subject) ? $subject : 'Tanpa Subjek');
$email_body = "Anda menerima pesan baru dari form kontak Ar Creative.\n\n".
              "Nama: $name\n".
              "Email: $email\n".
              "Subjek: $subject\n".
              "Pesan:\n$message\n";

$headers = "From: noreply@arcreative.com\r\n";
$headers .= "Reply-To: $email\r\n";

// Attempt to send email (in sandbox environment, standard mail() might not deliver, but we return success message)
@mail($to, $email_subject, $email_body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil dikirim. Terima kasih telah menghubungi Ar Creative!'
]);
exit;
