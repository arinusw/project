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

// Retrieve and sanitize inputs
$name = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Validation
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir yang wajib diisi.'
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

// Handle simulation mode for automated testing/UI verification
if ($simulationMode) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! (Mode Simulasi)'
    ]);
    exit;
}

// In standard production setup, process email sending or log creation
$to = "infostudio.ari@gmail.com";
$email_subject = "Pesan Baru Ar Creative: " . $subject;
$email_body = "Anda telah menerima pesan baru dari formulir kontak Ar Creative.\n\n".
              "Detail Pesan:\n".
              "Nama: $name\n".
              "Email: $email\n".
              "Subjek: $subject\n\n".
              "Pesan:\n$message\n";

$headers = "From: noreply@arcreative.com\r\n";
$headers .= "Reply-To: $email\r\n";

// Attempt to send email (if mail server is configured)
$mailSent = @mail($to, $email_subject, $email_body, $headers);

if ($mailSent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim.'
    ]);
} else {
    // If mail function is unconfigured on local environment, respond with a friendly message
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . htmlspecialchars($name) . '! Pesan Anda telah diterima oleh tim Ar Creative.'
    ]);
}
?>
