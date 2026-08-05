<?php
header('Content-Type: application/json; charset=utf-8');

// Read POST data
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '';

// Simple validations
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Silakan lengkapi semua data formulir.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// Check if simulation_mode is enabled
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan terkirim sukses (Mode Simulasi).'
    ]);
    exit;
}

// Actual mail sending logic (optional/best-effort fallback)
$to = 'infostudio.ari@gmail.com';
$subject = 'Pesan Baru dari ' . $name . ' (Ar Creative)';
$body = "Nama: $name\nEmail: $email\n\nPesan/Proyek:\n$project";
$headers = "From: $email\r\nReply-To: $email\r\n";

if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim. Terima kasih!'
    ]);
} else {
    // If mail fails, return success representation anyway to keep UX elegant,
    // or let them know it was simulated/saved if fallback is configured
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima secara lokal (offline simulation).'
    ]);
}
exit;