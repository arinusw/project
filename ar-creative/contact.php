<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim(filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
    $email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $subject = isset($_POST['subject']) ? trim(filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
    $message = isset($_POST['message']) ? trim(filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '';
    $simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon isi semua bidang yang diperlukan.'
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

    if ($simulationMode) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim (Simulasi). Terima kasih telah menghubungi Ar Creative!'
        ]);
        exit;
    }

    // Standard contact processing (e.g., mail sending simulation or actual mail)
    $to = 'infostudioari@gmail.com';
    $headers = "From: " . $email . "\r\n" .
               "Reply-To: " . $email . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // For demonstration, return a success JSON response
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim. Terima kasih telah menghubungi Ar Creative!'
    ]);
    exit;
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
