<?php
/**
 * Contact Form Handler for Ar Creative
 * Version 1.0
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<h1>Kesalahan</h1>";
        echo "<p>Mohon isi semua bidang formulir dengan benar.</p>";
        echo "<a href='index.html'>Kembali</a>";
        exit;
    }

    // Recipient
    $recipient = "infostudio.ari@gmail.com";

    // Email Content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email Headers
    $email_headers = "From: $name <$email>";

    // Send Email (simulation if mail server not configured)
    $success = @mail($recipient, "Pesan Kontak Ar Creative: $subject", $email_content, $email_headers);

    // Response
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Terima Kasih | Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f9f8ff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-container {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            h1 { color: #6e57e0; }
            p { color: #666; margin-bottom: 2rem; }
            .button {
                background: #6e57e0;
                color: white;
                padding: .75rem 1.5rem;
                border-radius: .5rem;
                text-decoration: none;
                transition: .3s;
            }
            .button:hover { background: #5a43cb; }
        </style>
    </head>
    <body>
        <div class="message-container">
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
} else {
    // If not a POST request
    header("Location: index.html");
}
?>
