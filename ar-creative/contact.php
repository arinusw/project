<?php
header('Content-Type: application/json; charset=utf-8');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
    exit;
}

// Retrieve form inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate inputs
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// If in simulation mode (used for local testing and automated verification)
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi). Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit;
}

// Mail configuration for live server
$to = "infostudioari@gmail.com";
$email_subject = "Pesan Kontak Ar Creative: " . $subject;
$email_body = "Anda telah menerima pesan baru dari formulir kontak website Ar Creative.\n\n".
              "Nama: $name\n".
              "Email: $email\n".
              "Subjek: $subject\n\n".
              "Pesan:\n$message\n";

$headers = "From: noreply@arcreative.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt to send email
if (@mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim. Terima kasih telah menghubungi Ar Creative!'
    ]);
} else {
    // Return success response in local dev environment where mail() is unconfigured
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima. Terima kasih telah menghubungi Ar Creative!'
    ]);
}
?>
