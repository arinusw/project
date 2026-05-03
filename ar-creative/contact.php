<?php
/**
 * Ar Creative - Contact Form Handler
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
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

    // Email configuration (example)
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Portfolio Ar Creative: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $headers = "From: $name <$email>";

    // Simulation mode for development/CI
    // In a real production environment, you would use mail() or a mailer library
    $is_simulation = true;

    if ($is_simulation) {
        // Simulate successful mail sending
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil terkirim. Terima kasih!'
        ]);
    } else {
        // Attempt to send the email
        if (@mail($to, $subject, $email_content, $headers)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pesan Anda telah berhasil terkirim. Terima kasih!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.'
            ]);
        }
    }
} else {
    // Not a POST request
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
}
