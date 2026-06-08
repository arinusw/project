<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX requests from the contact form
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['user_subject']) ? strip_tags(trim($_POST['user_subject'])) : '';
    $message = isset($_POST['user_message']) ? strip_tags(trim($_POST['user_message'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon isi semua field yang diperlukan.'
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

    // Simulation Mode Check
    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // Production Email Logic (Placeholder)
    // In a real environment, you would use mail() or a library like PHPMailer
    /*
    $to = "infostudio.ari@gmail.com";
    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $full_message = "Nama: $name\nEmail: $email\n\n$message";

    if(mail($to, $subject, $full_message, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan Anda telah berhasil dikirim!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti.']);
    }
    */

    // For now, return a generic success since it's a portfolio demo
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah diterima (Demo Mode).'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
}
?>
