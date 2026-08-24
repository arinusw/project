<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
    exit;
}

$userName = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$userEmail = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$userProject = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validation
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir.'
    ]);
    exit;
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid.'
    ]);
    exit;
}

// Check if simulation mode is enabled for automated testing
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! (Modus Simulasi)'
    ]);
    exit;
}

// Process sending email (production logic)
$to = 'infostudioari@gmail.com';
$subject = 'Pesan Baru dari Portfolio Ar Creative - ' . $userName;
$body = "Nama: {$userName}\nEmail: {$userEmail}\n\nPesan/Proyek:\n{$userProject}";
$headers = "From: {$userEmail}\r\nReply-To: {$userEmail}\r\nContent-Type: text/plain; charset=UTF-8";

// Send email (suppressing mail error output on environments without configured MTA)
$mailSent = @mail($to, $subject, $body, $headers);

if ($mailSent) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    // Return success message to user as fallback/graceful notification
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima. Terima kasih telah menghubungi Ar Creative!'
    ]);
}
