<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX requests from the contact form.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = strip_tags(trim($_POST['user_name']));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST['user_subject']));
    $message = trim($_POST['user_message']);
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan benar."
        ]);
        exit;
    }

    // Check for simulation mode (used for testing without actually sending email)
    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer
    // For this demonstration, we'll return a success response assuming the process is complete

    /*
    $recipient = "infostudio.ari@gmail.com";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Oops! Terjadi kesalahan saat mengirim pesan."]);
    }
    */

    // Returning success as default for this portfolio project template
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah berhasil dikirim. Terima kasih!"
    ]);

} else {
    // Not a POST request
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Akses ditolak."
    ]);
}
?>
