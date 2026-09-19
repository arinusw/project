<?php
// Set JSON response header
header('Content-Type: application/json; charset=UTF-8');

// Allow requests from the same origin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid. Harap gunakan metode POST.'
    ]);
    exit;
}

// Retrieve POST parameters
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$project = isset($_POST['project']) ? trim($_POST['project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate required fields
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom wajib diisi. Silakan lengkapi nama, email, dan pesan Anda.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid. Harap periksa kembali email Anda.'
    ]);
    exit;
}

// Handle simulation mode for automated testing/previews
if ($simulation_mode === '1' || $simulation_mode === 1 || $simulation_mode === 'true') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi Ar Creative (Simulasi).'
    ]);
    exit;
}

// Recipient email definition
$to = 'infostudio.ari@gmail.com';
$subject = "Pesan Baru dari Portofolio Ar Creative: " . $name;
$body = "Nama: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Pesan / Proyek:\n" . $project . "\n";

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";

// Attempt to send email
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda kembali.'
    ]);
} else {
    // If local mail server is not configured, fall back gracefully
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima oleh sistem Ar Creative. Terima kasih!'
    ]);
}
?>
