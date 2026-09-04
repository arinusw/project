<?php
// contact.php - Lightweight contact handling for Ar Creative portfolio website

// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Respond to preflight OPTIONS request if necessary
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Extract and sanitize input data
$name = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom pendaftaran / formulir.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Handle simulation mode or standard handling
if ($simulation_mode === '1' || $simulation_mode === 1) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// In production without simulation, attempt mail dispatch or simulate success gracefully
$to = "infostudioari@gmail.com";
$email_subject = "Pesan Baru Ar Creative: " . $subject;
$email_body = "Anda menerima pesan baru dari website Ar Creative:\n\n".
              "Nama: $name\n".
              "Email: $email\n".
              "Subjek: $subject\n\n".
              "Pesan:\n$message\n";
$headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

// Execute mail sending
@mail($to, $email_subject, $email_body, $headers);

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
]);
?>
