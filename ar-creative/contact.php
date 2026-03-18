<?php
/**
 * Contact form handler for Ar Creative
 * This script processes the contact form submission, sanitizes the data,
 * and provides a styled success response.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Ops! Ada masalah dengan pengiriman Anda. Pastikan formulir terisi dengan benar.";
        exit;
    }

    // Recipient email address
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // In a real environment, you would use mail()
    // mail($recipient, $subject, $email_content, $email_headers);

    // Provide a styled HTML response for the user
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Terima Kasih | Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: hsl(250, 60%, 99%);
                color: hsl(250, 8%, 15%);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                text-align: center;
                background-color: #fff;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                max-width: 500px;
            }
            h1 { color: hsl(250, 69%, 61%); }
            p { margin-bottom: 2rem; color: hsl(250, 8%, 45%); }
            .button {
                display: inline-block;
                background-color: hsl(250, 69%, 61%);
                color: #fff;
                padding: .75rem 1.5rem;
                border-radius: .5rem;
                text-decoration: none;
                transition: .3s;
            }
            .button:hover { background-color: hsl(250, 57%, 53%); }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali melalui email atau WhatsApp.</p>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php

} else {
    // Not a POST request, redirect to index
    header("Location: index.html");
    exit;
}
?>
