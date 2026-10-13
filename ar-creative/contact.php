<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
    exit;
}

// Retrieve and sanitize input fields
$name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : null;

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap lengkapi semua kolom formulir kontak.'
    ]);
    exit;
}

// Validate email address format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Check for simulation mode (used in UI testing) or normal submission
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Simulasi berhasil: Pesan Anda telah diterima oleh tim Ar Creative!'
    ]);
    exit;
}

// Standard contact message success response
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
]);
exit;
