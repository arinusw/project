<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom formulir yang diperlukan.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email yang Anda masukkan tidak valid.'
        ]);
        exit;
    }

    // Success response in Bahasa Indonesia
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil terkirim ke Ar Creative.'
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
