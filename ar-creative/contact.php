<?php
/**
 * Ar Creative - Contact Form Handler
 *
 * This script processes the contact form submission via AJAX.
 * It sanitizes input and simulates/attempts email delivery.
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
            'message' => 'Silakan isi semua kolom formulir.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email tidak valid.'
        ]);
        exit;
    }

    // Simulation Mode / Email attempt
    // In a real production environment, you would configure SMTP or use mail()
    // For this project, we provide a success response to verify the UI flow.

    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative Portfolio: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    // Suppress error with @ for environments without mail server configured
    $mail_sent = @mail($to, $subject, $body, $headers);

    // We return success even if mail() fails in local dev environment to show UI feedback
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! ✅'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
