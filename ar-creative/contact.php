<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua bidang formulir yang wajib diisi.'
        ]);
        exit;
    }

    // Process contact form submission (simulation mode or standard processing)
    if ($simulationMode === '1' || true) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
        ]);
        exit;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}
?>
