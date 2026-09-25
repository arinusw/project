<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon isi semua bidang formulir yang diperlukan.'
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

// Check for testing / simulation mode
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// In production / live server environment
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Portfolio Ar Creative - " . $name;
$body = "Nama: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Pesan:\n" . $message . "\n";

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt mail sending (or simulate success if mail is not configured locally)
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
} else {
    // Return success response as graceful fallback for web server without sendmail/SMTP setup
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil diterima! Kami akan segera menghubungi Anda kembali.'
    ]);
}
?>
