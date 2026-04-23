<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        // In a real scenario, you would use mail() here
        // @mail('infostudio.ari@gmail.com', 'Contact Form: ' . $name, $message, 'From: ' . $email);

        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih, pesan Anda telah terkirim (Simulasi).'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon isi semua field dengan benar.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
}
?>
