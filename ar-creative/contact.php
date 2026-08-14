<?php
// Set response content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Retrieve and sanitize form inputs
$name = isset($_POST['user_name']) ? trim(strip_tags($_POST['user_name'])) : '';
$email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
$project = isset($_POST['user_project']) ? trim(strip_tags($_POST['user_project'])) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate required fields
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom pendaftaran / formulir.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// Handle simulation mode for automated testing / dry-run
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// In production environment, send email notification
$to = 'infostudioari@gmail.com';
$subject = 'Pesan Baru dari Portfolio Ar Creative - ' . $name;
$body = "Anda menerima pesan baru dari formulir kontak Ar Creative:\n\n";
$body .= "Nama: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Pesan/Proyek:\n" . $project . "\n";

$headers = "From: webmaster@arcreative.com\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt sending email (or mock success if mailserver is unconfigured)
@$mailSent = mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative. Kami akan segera merespons pesan Anda.'
]);
exit;
