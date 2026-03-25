<?php
/**
 * Contact form handler for Ar Creative
 * Developed for digital studio and education services
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Silakan lengkapi formulir dengan benar.";
        exit;
    }

    // Email configuration (placeholder for actual use)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name via Ar Creative";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Attempt to send email
    // Note: This will only work on servers with a configured mail system.
    @mail($recipient, $subject, $email_content, $email_headers);

    // Provide a styled success response
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pesan Terkirim - Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
            h1 { color: #6e57e0; }
            p { color: #666; margin-bottom: 2rem; }
            .button {
                background: #6e57e0;
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: 0.3s;
            }
            .button:hover { background: #5a43cb; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali melalui email.</p>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
} else {
    // Not a POST request
    header("Location: index.html");
    exit;
}
?>
