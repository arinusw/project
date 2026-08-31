<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

// Retrieve form inputs
$userName = trim($_POST['user_name'] ?? '');
$userEmail = trim($_POST['user_email'] ?? '');
$userProject = trim($_POST['user_project'] ?? '');
$simulationMode = $_POST['simulation_mode'] ?? null;

// Validate inputs
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir yang wajib diisi.'
    ]);
    exit;
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang dimasukkan tidak valid.'
    ]);
    exit;
}

// If simulation_mode is enabled or processing standard submission
// Return success message in Bahasa Indonesia
echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($userName) . '! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.'
]);
exit;
