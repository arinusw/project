<?php
/**
 * Ar Creative - Contact Form Handler
 * Simple PHP script to handle contact form submissions.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize Inputs
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // 2. Validation
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Harap isi semua bidang form.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Alamat email tidak valid.']);
        exit;
    }

    // 3. Handle Simulation Mode
    if ($simulationMode === '1') {
        echo json_encode(['status' => 'success', 'message' => 'Mode Simulasi: Pesan berhasil diterima!']);
        exit;
    }

    // 4. Send Email (Actual Logic)
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Website Ar Creative: $name";

    // Construct email body
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    // Note: In a real environment, you might need additional configuration (SMTP)
    $headers = "From: $name <$email>";

    // Attempt to send
    if (mail($to, $subject, $email_content, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Terima kasih! Pesan Anda telah terkirim.']);
    } else {
        // In many local environments, mail() will fail unless configured.
        // We return a specialized error to help the user know why it failed.
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti atau hubungi via WhatsApp.']);
    }

} else {
    // Not a POST request
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
}
?>
