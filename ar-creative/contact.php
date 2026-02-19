<?php
/**
 * Ar Creative Contact Form Handler
 * Language: Bahasa Indonesia
 * Code Comments: English
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Return error
        http_response_code(400);
        echo "Silakan lengkapi formulir dengan benar.";
        exit;
    }

    // Recipient email (from memory)
    $recipient = "infostudio.ari@gmail.com";

    // Email subject
    $subject = "Pesan Baru dari $name via Ar Creative Website";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // In a real PHP environment with mail server, use mail() function
    @mail($recipient, $subject, $email_content, $email_headers);

    // For this demo/experimental project, we'll show a success message
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
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f9f9ff;
                text-align: center;
                color: #2d3436;
            }
            .container {
                background: white;
                padding: 50px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                max-width: 500px;
            }
            h1 { color: #6c5ce7; margin-bottom: 20px; }
            p { margin-bottom: 30px; color: #636e72; }
            a {
                display: inline-block;
                background-color: #6c5ce7;
                color: white;
                padding: 12px 30px;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            a:hover {
                background-color: #5849c4;
                transform: translateY(-3px);
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil kami terima. Kami akan segera menghubungi Anda kembali melalui email.</p>
            <a href='index.html'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
} else {
    // Not a POST request
    header("Location: index.html");
    exit;
}
?>
