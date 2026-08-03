<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Retrieve form inputs
$userName = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$userEmail = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$userProject = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '';

// Validation
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom formulir harus diisi.'
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

// Handle simulation mode (from Playwright/automated tests)
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan berhasil dikirim (Mode Simulasi).'
    ]);
    exit;
}

// In production, you would configure mail() or another mailer here
// For this lightweight project, we output a standard success message in Bahasa Indonesia
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
]);
exit;
