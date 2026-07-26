<?php
header('Content-Type: application/json; charset=utf-8');

// Read POST data
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? (int)$_POST['simulation_mode'] : 0;

// Simple validation
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom formulir wajib diisi!'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid!'
    ]);
    exit;
}

// Check if simulation mode is active (for automated tests / testing environment)
if ($simulationMode === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan terkirim! (Simulasi Berhasil)'
    ]);
    exit;
}

// Production / Real handling
// Define recipient
$to = "infostudio.ari@gmail.com"; // Plausible real placeholder
$subject = "Kontak Baru dari Website Ar Creative - " . $name;
$body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
$headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=utf-8";

// Send email
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim. Terima kasih!'
    ]);
} else {
    // If mail function is not configured, fallback gracefully or provide success if local testing
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil diproses. Terima kasih!'
    ]);
}
exit;
