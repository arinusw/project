<?php
/**
 * Ar Creative Contact Form Handler
 * Version: 1.0
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitization
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

    // Validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua field yang tersedia.'
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

    // Simulation Mode: Always return success for this project
    // In a real environment, you would use mail() or a library like PHPMailer

    /*
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    @mail($to, $subject, $body, $headers);
    */

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim. Terima kasih!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
}
?>
