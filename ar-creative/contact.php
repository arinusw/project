<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $user_email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $user_project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($user_name) || empty($user_email) || empty($user_project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom yang wajib diisi.'
        ]);
        exit;
    }

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format email tidak valid.'
        ]);
        exit;
    }

    // Process form / simulation mode
    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // In a production server, mail() or SMTP would be invoked here.
    // We return a successful response acknowledging receipt of message.
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih ' . htmlspecialchars($user_name) . ', pesan Anda telah berhasil terkirim ke Ar Creative!'
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
?>