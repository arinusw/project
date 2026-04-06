<?php
/**
 * Ar Creative Contact Form Handler
 * Bahasa Indonesia Response
 */

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
        echo "Ops! Terjadi masalah dengan pengiriman Anda. Silakan lengkapi formulir dan coba lagi.";
        exit;
    }

    // Recipient email address
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan Baru dari Ar Creative Portfolio oleh $name";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Simulation mode for development
    $is_simulation = true;

    if ($is_simulation) {
        // In simulation mode, we always return success
        $success = true;
    } else {
        // Send the email.
        $success = @mail($recipient, $subject, $email_content, $email_headers);
    }

    if ($success) {
        // Set a 200 (ok) response code.
        http_response_code(200);
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Pesan Terkirim | Ar Creative</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    background-color: #f9f9ff;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .card {
                    background: white;
                    padding: 2rem;
                    border-radius: 1rem;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    text-align: center;
                    max-width: 400px;
                }
                h1 { color: #6e57e0; margin-bottom: 1rem; }
                p { color: #6d6a7c; margin-bottom: 2rem; }
                .btn {
                    background-color: #6e57e0;
                    color: white;
                    padding: .75rem 1.5rem;
                    border-radius: .5rem;
                    text-decoration: none;
                    font-weight: 600;
                    transition: .3s;
                }
                .btn:hover { background-color: #5a43cb; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h1>Terima Kasih!</h1>
                <p>Pesan Anda telah berhasil terkirim. Tim Ar Creative akan segera menghubungi Anda melalui email.</p>
                <a href='../index.html' class='btn'>Kembali ke Beranda</a>
            </div>
        </body>
        </html>";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Ups! Terjadi kesalahan saat mengirim pesan Anda.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
