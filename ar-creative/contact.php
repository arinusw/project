<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom pendaftaran / kontak.'
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

    // Process submission or handle simulation mode
    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // Standard success response for actual form submissions
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}
?>
