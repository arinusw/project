<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST['user_subject']));
    $message = strip_tags(trim($_POST['user_message']));
    $simulation = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua field dengan benar.'
        ]);
        exit;
    }

    if ($simulation) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
    } else {
        // In a real scenario, you would use mail() or a library like PHPMailer
        // For this task, we will just return a success message
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim. Terima kasih!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
