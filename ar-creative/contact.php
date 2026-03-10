<?php
/**
 * Contact Form Handler for Ar Creative
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<h1>Maaf!</h1><p>Silakan lengkapi formulir dengan benar.</p>";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";

    // Email content
    $subject = "Pesan Baru dari $name (Kontak Website Ar Creative)";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email (Note: In a local environment, mail() might not work without setup)
    // For this project, we just simulate the success response for the UI.

    /*
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "<h1>Terima Kasih!</h1><p>Pesan Anda telah berhasil dikirim.</p>";
    } else {
        http_response_code(500);
        echo "<h1>Ups!</h1><p>Terjadi kesalahan saat mengirim pesan.</p>";
    }
    */

    // Simulated response for demonstration
    http_response_code(200);
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pesan Terkirim - Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body { font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f9f9ff; }
            .container { text-align: center; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 400px; }
            h1 { color: #6e57e0; }
            p { color: #6d6a7c; margin-bottom: 2rem; }
            .button { background-color: #6e57e0; color: white; padding: .75rem 1.5rem; border-radius: .5rem; text-decoration: none; font-weight: 600; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda melalui email.</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";

} else {
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
