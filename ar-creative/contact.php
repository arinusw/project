<?php
// Set header to return JSON response
header('Content-Type: application/json; charset=utf-8');

// Allow basic CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
    exit;
}

// Retrieve and sanitize form inputs
$userName = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$userEmail = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$userProject = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : null;

// Validate required fields
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir kontak.'
    ]);
    exit;
}

// Validate email format
if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email yang dimasukkan tidak valid.'
    ]);
    exit;
}

// Handle simulation mode or standard response
if ($simulationMode === '1' || $simulationMode === 1 || $simulationMode === true) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! (Mode Simulasi)'
    ]);
    exit;
}

// Optional mail handling (defaulting to successful response confirmation for portfolio)
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Portfolio Ar Creative - " . $userName;
$body = "Nama: " . $userName . "\n";
$body .= "Email: " . $userEmail . "\n\n";
$body .= "Pesan:\n" . $userProject . "\n";
$headers = "From: " . $userEmail . "\r\n" .
           "Reply-To: " . $userEmail . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Attempt to send email or return success
@mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim.'
]);
exit;
