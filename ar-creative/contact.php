<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes AJAX requests from the contact form.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? (bool)$_POST['simulation_mode'] : false;

    // Basic validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi semua kolom input.'
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

    // Handle simulation mode (for testing)
    if ($simulation_mode) {
        // Simulate a delay
        usleep(500000); // 0.5 seconds

        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan terkirim (Mode Simulasi)!'
        ]);
        exit;
    }

    // Production mail handling (optional/if configured)
    $to = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Ar Creative: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    // Use @ to suppress errors on unconfigured local servers
    if (@mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim!'
        ]);
    } else {
        // Fallback for environments without mail server
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengirim pesan melalui server email. Silakan coba WhatsApp.'
        ]);
    }
} else {
    // Handle non-POST requests
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
}
?>
