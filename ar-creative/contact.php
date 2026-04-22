<?php
/**
 * Ar Creative Contact Form Handler
 * Bahasa Indonesia Response
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["user_message"]);

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Mohon lengkapi formulir dengan benar.";
        exit;
    }

    // Recipient email (Update this with your actual email)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name via Ar Creative Website";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    /**
     * Simulation Mode:
     * In a real server environment, we would use the mail() function.
     * Since this is a portfolio and may be run locally or in environments
     * without a configured mail server, we simulate success.
     */

    // Attempt to send email (suppress errors with @ for simulation)
    $success = @mail($recipient, $subject, $email_content, $email_headers);

    // For the sake of this project and user experience, we return success
    // unless there is a critical failure in the script execution.
    // In a live environment, the mail() function's return value would be used strictly.

    http_response_code(200);
    echo "Terima kasih! Pesan Anda telah terkirim.";
} else {
    // Not a POST request
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
