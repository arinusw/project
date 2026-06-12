<?php
/**
 * Ar Creative Contact Form Handler
 *
 * This script handles AJAX requests from the contact form.
 * It includes sanitization and supports a simulation mode.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    // Remove newlines from name to prevent header injection if it were used in mail() headers
    $name = str_replace(array("\r", "\n"), '', $name);

    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['user_subject']) ? strip_tags(trim($_POST['user_subject'])) : '';
    $message = isset($_POST['user_message']) ? strip_tags(trim($_POST['user_message'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
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

    // Check for simulation mode
    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // Production mail handling (commented out as per project environment limitations)
    /*
    $to = "infostudio.ari@gmail.com";
    $headers = "From: $name <$email>" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $mail_body = "Nama: $name\n";
    $mail_body .= "Email: $email\n";
    $mail_body .= "Subjek: $subject\n\n";
    $mail_body .= "Pesan:\n$message";

    if (mail($to, $subject, $mail_body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.'
        ]);
    }
    */

    // Default response for production if mail is not sent (placeholder)
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima. Kami akan segera menghubungi Anda!'
    ]);

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
