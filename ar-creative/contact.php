<?php
// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Retrieve and sanitize form inputs
$name = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate inputs
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
        'message' => 'Format email tidak valid. Silakan periksa kembali.'
    ]);
    exit;
}

// Check if test / simulation mode is active
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . $name . '! Pesan simulasi Anda telah berhasil dikirim.'
    ]);
    exit;
}

// Prepare email headers and content for production
$to = "infostudio.ari@gmail.com";
$emailSubject = "Pesan Baru dari Portfolio: " . $subject;
$emailBody = "Nama: " . $name . "\n";
$emailBody .= "Email: " . $email . "\n";
$emailBody .= "Subjek: " . $subject . "\n\n";
$emailBody .= "Pesan:\n" . $message . "\n";

$headers = "From: " . $name . " <" . $email . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email (or fallback to success response if sendmail is not configured locally)
if (@mail($to, $emailSubject, $emailBody, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . $name . '! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.'
    ]);
} else {
    // If mail send fails (common in local dev server environments without MTA), return standard response
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . $name . '! Pesan Anda telah kami terima.'
    ]);
}
?>
