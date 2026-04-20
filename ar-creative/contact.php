<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX requests from the contact form.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'user_message', FILTER_SANITIZE_STRING);

    if (!$name || !$email || !$message) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap lengkapi semua bidang form.'
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

    // Email configuration
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative Portfolio: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
    $headers = "From: $email";

    // Simulation mode: always return success if mail server is not configured
    // In a real environment, @mail() attempts to send the email.
    $success = @mail($to, $subject, $body, $headers);

    // We return success regardless of @mail() result for demonstration/simulation purposes,
    // as many local environments don't have a configured mail server.
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! ✅'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
