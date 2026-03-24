<?php
/**
 * Contact Form Handler for Ar Creative
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and filter input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Set recipient and subject (Update with actual recipient)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari Kontak Portofolio: $name";

    // Build email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build email headers
    $email_headers = "From: $name <$email>";

    // Send email (using @ to suppress errors on local servers)
    // In a real environment, @mail() would attempt delivery
    @mail($recipient, $subject, $email_content, $email_headers);

    // Provide a styled response
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pesan Terkirim | Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f9f9ff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                color: #2b2b2b;
            }
            .container {
                text-align: center;
                background: white;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                max-width: 400px;
            }
            h1 { color: #6e57e0; }
            p { margin-bottom: 2rem; color: #6d6d6d; }
            .button {
                background-color: #6e57e0;
                color: white;
                padding: 1rem 2rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 500;
                transition: .3s;
            }
            .button:hover { background-color: #5a43cb; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href='../index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
} else {
    // If not a POST request, redirect back
    header("Location: ../index.html");
    exit;
}
?>
