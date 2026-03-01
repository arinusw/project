<?php
/**
 * Author: Ar Creative
 * Description: Simple PHP contact form handler with basic security.
 * Language Convention: English comments, Bahasa Indonesia response.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Maaf, ada masalah dengan pengiriman Anda. Silakan lengkapi formulir dan coba lagi.";
        exit;
    }

    // Set the recipient email address.
    // FIXME: Update this to your desired email address.
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Kontak Baru dari Portfolio Ar Creative: $name";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (success) response code.
        http_response_code(200);
        echo "Terima kasih! Pesan Anda telah terkirim.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Maaf, terjadi kesalahan saat mengirim pesan Anda.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
