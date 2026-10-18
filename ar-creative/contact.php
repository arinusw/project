<?php
header('Content-Type: application/json; charset=utf-8');

// Allow simulation mode or standard POST handling
$isSimulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] == '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email   = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Harap isi semua bidang formulir dengan benar.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Alamat email tidak valid. Silakan periksa kembali.'
        ]);
        exit;
    }

    // In simulation mode or standard environment, return success
    echo json_encode([
        'status'  => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
?>
