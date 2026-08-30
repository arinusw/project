<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
    $is_simulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom yang tersedia.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format alamat email tidak valid.'
        ]);
        exit;
    }

    if ($is_simulation) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim (Simulasi Mode)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // In actual production, mail() or an SMTP client could be used here.
    // For local portfolio context, simulate successful handling:
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
