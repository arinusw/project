<?php
/**
 * Ar Creative Contact Form Handler
 * Simple PHP script to handle contact form submissions.
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
            'message' => 'Format email tidak valid.'
        ]);
        exit;
    }

    // Simulation Mode: In a real scenario, you would use mail() or a mailer library here.
    // For this project, we'll simulate a successful delivery.

    /*
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative: " . $name;
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    @mail($to, $subject, $body, $headers);
    */

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah terkirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
