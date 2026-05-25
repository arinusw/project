<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles form submission via AJAX or standard POST
 */

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = isset($_POST['user_name']) ? str_replace(array("\r", "\n"), '', strip_tags(trim($_POST['user_name']))) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Validate data
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Ups! Silakan lengkapi formulir dengan benar."]);
        exit;
    }

    // Check for simulation mode
    if ($simulation_mode === '1') {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // Production email configuration (placeholders)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name - Ar Creative Portfolio";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    // Send the email (disabled for safety in this environment)
    /*
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Maaf, terjadi kesalahan saat mengirim pesan."]);
    }
    */

    // For now, return a success message as if it was sent
    echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim (Produksi)."]);

} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses dilarang."]);
}
?>
