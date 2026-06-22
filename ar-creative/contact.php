<?php
/**
 * Ar Creative Contact Form Handler
 *
 * This script processes the contact form submission.
 * It supports a simulation mode for development and testing.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitization
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Prevent header injection
    $name = str_replace(array("\r", "\n"), '', $name);

    // Basic validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon isi semua bidang formulir.'
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

    // Handle Simulation Mode
    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // Real mail sending logic would go here
    // For this portfolio, we'll return a success message as if it were sent
    // in a production environment you'd use mail() or a library like PHPMailer

    /*
    $to = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Portofolio Ar Creative: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan Anda telah terkirim!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan.']);
    }
    */

    // Returning success for demonstration purposes
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
