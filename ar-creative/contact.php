<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Maaf, silakan lengkapi formulir dan coba lagi.";
        exit;
    }

    // Set the recipient email address.
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan Baru dari Portofolio Ar Creative: $name";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    // Note: mail() function requires a properly configured mail server.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (ok) response code.
        http_response_code(200);
        echo "Terima kasih! Pesan Anda telah dikirim.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Ups! Terjadi kesalahan dan pesan Anda tidak dapat dikirim.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
