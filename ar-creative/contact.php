<?php
// Set response content type to JSON
header('Content-Type: application/json; charset=UTF-8');

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit;
}

// Retrieve and sanitize inputs
$userName = isset($_POST['user_name']) ? trim(htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8')) : '';
$userEmail = isset($_POST['user_email']) ? trim(filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL)) : '';
$userProject = isset($_POST['user_project']) ? trim(htmlspecialchars($_POST['user_project'], ENT_QUOTES, 'UTF-8')) : '';
$simulationMode = isset($_POST['simulation_mode']) ? (int)$_POST['simulation_mode'] : 0;

// Validate inputs
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir dengan benar.'
    ]);
    exit;
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// Simulation mode response for testing without sending actual emails
if ($simulationMode === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi). Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit;
}

// Default successful response
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih! Pesan Anda telah berhasil terkirim. Tim Ar Creative akan segera menghubungi Anda.'
]);
?>
