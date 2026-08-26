<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom formulir!'
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

    // Handle simulation mode or mock response
    if ($simulationMode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim (Mode Simulasi)! Kami akan segera menghubungi Anda.'
        ]);
        exit;
    }

    // Standard contact processing simulation
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
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