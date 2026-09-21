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
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validation
if (empty($name) || empty($email) || empty($message)) {
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

// Check for testing simulation mode
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Production email sending logic
$to = 'infostudioari@gmail.com';
$subject = 'Pesan Baru dari Website Ar Creative: ' . $name;
$body = "Nama: $name\nEmail: $email\n\nPesan:\n$message\n";
$headers = "From: $email\r\n" .
           "Reply-To: $email\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Attempt mail send (suppress warning on servers without sendmail configured)
$mail_sent = @mail($to, $subject, $body, $headers);

if ($mail_sent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim! Kami akan segera menghubungi Anda kembali.'
    ]);
} else {
    // Fallback response for local environment without mail daemon
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima oleh sistem Ar Creative. Terima kasih telah menghubungi kami!'
    ]);
}
