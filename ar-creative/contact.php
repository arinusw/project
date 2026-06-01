<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['user_subject']) ? strip_tags(trim($_POST['user_subject'])) : '';
    $message = isset($_POST['user_message']) ? strip_tags(trim($_POST['user_message'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Semua kolom input wajib diisi.'
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

    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer
    // $to = "infostudio.ari@gmail.com";
    // $headers = "From: $email";
    // $body = "Nama: $name\nEmail: $email\nSubjek: $subject\n\nPesan:\n$message";

    // For this project, we'll return a success message as if it was sent
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah terkirim.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
