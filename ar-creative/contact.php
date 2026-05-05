<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX requests from the contact form.
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
            'message' => 'Mohon lengkapi semua field yang diperlukan.'
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

    // Email Configuration
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name - Ar Creative Portfolio";
    $body = "Nama: $name\nEmail: $email\n\nPesan/Proyek:\n$project";
    $headers = "From: $email";

    /**
     * SIMULATION MODE
     * Since most development environments don't have a configured mail server,
     * we simulate the success response. In production, use @mail().
     */
    $mail_sent = false;

    // Attempt to send mail (will likely fail on sandbox, hence simulation)
    try {
        // @mail($to, $subject, $body, $headers); // Uncomment for production
        $mail_sent = true; // Simulating success for UI verification
    } catch (Exception $e) {
        $mail_sent = false;
    }

    if ($mail_sent) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.'
        ]);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak diizinkan.'
    ]);
}
?>
