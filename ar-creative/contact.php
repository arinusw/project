<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Kesalahan Pengiriman</title>
            <link rel='stylesheet' href='css/styles.css'>
            <style>
                .message-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class='message-container'>
                <h1 class='section__title'>Ups!</h1>
                <p>Silakan lengkapi formulir dengan benar.</p>
                <br>
                <a href='index.html' class='button'>Kembali</a>
            </div>
        </body>
        </html>";
        exit;
    }

    // Prepare email parameters
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // In a real server, we would call mail():
    // mail($recipient, $subject, $email_content, $email_headers);

    // Provide a success response
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pesan Terkirim</title>
        <link rel='stylesheet' href='css/styles.css'>
        <style>
            .message-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class='message-container'>
            <h1 class='section__title'>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim ke Ar Creative. Kami akan segera menghubungi Anda.</p>
            <br>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
} else {
    // Not a POST request
    header("Location: index.html");
}
?>
