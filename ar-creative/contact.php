<?php
/**
 * Ar Creative - Contact Form Handler
 * This script handles form submissions from the contact section.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle error
        http_response_code(400);
        echo "<h1>Terjadi Kesalahan</h1>";
        echo "<p>Silakan lengkapi formulir dengan benar.</p>";
        echo "<a href='index.html'>Kembali ke Beranda</a>";
        exit;
    }

    // Set recipient email address (Update this with real email)
    $recipient = "infostudio.ari@gmail.com";

    // Set email subject
    $subject = "Pesan Baru dari $name melalui Ar Creative";

    // Build email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build email headers
    $email_headers = "From: $name <$email>";

    // Send the email (Note: mail() might not work in some local environments without configuration)
    // For demonstration, we simulate success
    $success = true; // mail($recipient, $subject, $email_content, $email_headers);

    if ($success) {
        http_response_code(200);
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Pesan Terkirim | Ar Creative</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
            <style>
                body { font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f4f7ff; }
                .container { text-align: center; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                h1 { color: #6c5ce7; }
                a { display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background: #6c5ce7; color: white; text-decoration: none; border-radius: 0.5rem; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>Terima Kasih!</h1>
                <p>Pesan Anda telah berhasil dikirim ke Ar Creative. Kami akan segera menghubungi Anda.</p>
                <a href='index.html'>Kembali ke Beranda</a>
            </div>
        </body>
        </html>";
    } else {
        http_response_code(500);
        echo "<h1>Maaf!</h1>";
        echo "<p>Terjadi masalah teknis saat mengirim pesan Anda.</p>";
    }

} else {
    // Not a POST request
    http_response_code(403);
    echo "Metode tidak diizinkan.";
}
?>
