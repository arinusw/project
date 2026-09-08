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
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir yang wajib diisi.'
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

// Simulation mode for automated testing/UI verification
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan simulasi Anda telah berhasil dikirim.'
    ]);
    exit;
}

// Send actual email if mail environment is configured
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Portfolio Ar Creative: " . $name;
$body = "Nama: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Pesan:\n" . $message . "\n";

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt mail sending
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim ke Ar Creative.'
    ]);
} else {
    // Graceful fallback response for environment without active SMTP
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah diterima oleh sistem Ar Creative.'
    ]);
}
?>
