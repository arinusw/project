<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX requests from the contact form.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom yang diperlukan.'
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

    // Prevent header injection by removing newlines from the name
    $name = str_replace(["\r", "\n"], '', $name);

    // Simulation Mode Check
    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer
    /*
    $to = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Portofolio: " . $name;
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan Anda telah berhasil dikirim!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Maaf, terjadi kesalahan saat mengirim pesan.']);
    }
    */

    // Since we don't have a mail server in this environment, we'll return a success message for now.
    // This provides a good user experience for the demo.
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil diterima! (Mode Demo)'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
