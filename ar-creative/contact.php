<?php
header('Content-Type: application/json; charset=UTF-8');

// Allow CORS if necessary for local testing
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}

// Retrieve form input data
$userName = trim($_POST['user_name'] ?? '');
$userEmail = trim($_POST['user_email'] ?? '');
$userProject = trim($_POST['user_project'] ?? '');
$simulationMode = trim($_POST['simulation_mode'] ?? '0');

// Validate inputs
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir yang diperlukan.'
    ]);
    exit;
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Handle simulated mode or standard email simulation
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi). Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit;
}

// Default standard success response for website visitors
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
]);
?>
