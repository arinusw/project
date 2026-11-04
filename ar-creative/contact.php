<?php
// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Retrieve and sanitize input fields
$userName = isset($_POST['user_name']) ? trim(strip_tags($_POST['user_name'])) : '';
$userEmail = isset($_POST['user_email']) ? trim(strip_tags($_POST['user_email'])) : '';
$userProject = isset($_POST['user_project']) ? trim(strip_tags($_POST['user_project'])) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : null;

// Validate non-empty fields
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap lengkapi semua kolom formulir (nama, email, dan pesan).'
    ]);
    exit;
}

// Validate email format
if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Simulation mode for tests
if ($simulationMode === '1' || $simulationMode === 1 || $simulationMode === true) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, pesan Anda telah berhasil dikirim! (Mode Simulasi)'
    ]);
    exit;
}

// Optional mail dispatch
$to = "infostudioari@gmail.com";
$subject = "Pesan Kontak Ar Creative dari " . $userName;
$body = "Nama: " . $userName . "\n";
$body .= "Email: " . $userEmail . "\n\n";
$body .= "Pesan:\n" . $userProject . "\n";
$headers = "From: " . $userEmail . "\r\n" .
           "Reply-To: " . $userEmail . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

@mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih! Pesan Anda telah terkirim. Tim Ar Creative akan segera menghubungi Anda.'
]);
exit;
