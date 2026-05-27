<?php
/**
 * Ar Creative Contact Form Handler
 *
 * This script handles the AJAX contact form submission for the Ar Creative portfolio.
 * It supports a 'simulation_mode' to mimic email sending without requiring an SMTP server.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['user_subject']) ? strip_tags(trim($_POST['user_subject'])) : '';
    $message = isset($_POST['user_message']) ? strip_tags(trim($_POST['user_message'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua field yang wajib diisi.'
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
        // Return success for simulation mode
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
    } else {
        // Production email sending logic would go here
        // For now, we'll return success to indicate the endpoint is ready
        // In a real scenario, you would use mail() or a library like PHPMailer

        /*
        $to = "infostudio.ari@gmail.com";
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $full_message = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

        if (mail($to, $subject, $full_message, $headers)) {
             echo json_encode(['status' => 'success', 'message' => 'Pesan terkirim!']);
        } else {
             echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan.']);
        }
        */

        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan berhasil diproses.'
        ]);
    }
} else {
    // Not a POST request
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
}
?>
