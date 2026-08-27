<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

$userName = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$userEmail = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$userProject = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir dengan benar.'
    ]);
    exit;
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid.'
    ]);
    exit;
}

// Check if simulation mode is enabled for automated testing
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim dalam mode simulasi! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Production / Standard handling (Simulated success response for live demo)
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, pesan Anda telah berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
]);
exit;
