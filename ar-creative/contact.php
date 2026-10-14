<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom formulir kontak.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $simulationMode !== '1') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format email tidak valid.'
        ]);
        exit;
    }

    // Process message (In production, send email or store to database)
    // For local simulation mode or regular submission return success response in Bahasa Indonesia
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
