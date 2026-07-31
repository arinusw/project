<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit;
}

// Extract parameters
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '';

// Validation
if (empty($name) || empty($email) || empty($project)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom input wajib diisi.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// Process
if ($simulationMode === '1') {
    // Return simulated success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim (Simulasi)!'
    ]);
    exit;
}

// Non-simulation mode (production email sending fallback)
$to = 'infostudio@ari.com'; // Fallback / mock target
$subject = 'Ar Creative Contact Form: ' . $name;
$body = "Nama: $name\nEmail: $email\nPesan/Projek:\n$project";
$headers = "From: $email\r\nReply-To: $email\r\n";

if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim!'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengirim pesan. Silakan coba beberapa saat lagi.'
    ]);
}
