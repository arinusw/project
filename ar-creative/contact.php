<?php
header('Content-Type: application/json; charset=utf-8');

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}

// Extract form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Validation
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom pendaftaran / pesan.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid.'
    ]);
    exit;
}

// In simulation mode or standard demo mode, simulate success response
if ($simulationMode || true) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}
?>