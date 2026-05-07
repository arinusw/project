<?php
/**
 * Ar Creative - Contact Form Handler
 *
 * This script handles AJAX requests from the contact form.
 * It performs basic sanitization and simulates mail delivery.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Get and sanitize input
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

    // 2. Validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua bidang form.'
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

    // 3. Prepare Email (Simulation)
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Portofolio Ar Creative: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    /**
     * In a real production environment, you would use mail() or a library like PHPMailer.
     * For this project, we simulate a successful delivery.
     */

    // Simulate a slight delay for realism
    // usleep(500000); // 0.5 seconds

    // Use @mail() to attempt delivery but suppress errors if mail server is not configured
    // @mail($to, $subject, $body, $headers);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! ✅'
    ]);
} else {
    // Handle non-POST requests
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
