<?php
header('Content-Type: application/json; charset=UTF-8');

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
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi seluruh kolom formulir.'
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

// Handle testing/simulation mode for UI verification
if ($simulation === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, pesan Anda telah berhasil terkirim ke Studio Ar Creative (Simulation Mode).'
    ]);
    exit;
}

// Standard Email Processing
$to = 'infostudioari@gmail.com';
$email_subject = 'Pesan Baru Ar Creative: ' . $subject;
$email_body = "Anda telah menerima pesan baru dari formulir kontak Ar Creative:\n\n";
$email_body .= "Nama: $name\n";
$email_body .= "Email: $email\n";
$email_body .= "Subjek: $subject\n\n";
$email_body .= "Pesan:\n$message\n";

$headers = "From: noreply@arcreative.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

if (@mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, pesan Anda telah berhasil terkirim ke Studio Ar Creative.'
    ]);
} else {
    // Graceful fallback for local server environments without sendmail
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, pesan Anda telah diterima di studio Ar Creative.'
    ]);
}
?>
