<?php
/**
 * Ar Creative - Contact Form Handler
 * Security: Sanitize inputs and escape outputs.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Check if data is valid
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit
        http_response_code(400);
        display_message("Oops! Ada masalah dengan pengiriman Anda. Pastikan formulir diisi dengan benar.", "error");
        exit;
    }

    // Recipient email (using the one from memory)
    $recipient = "infostudio.ari@gmail.com";

    // Build the email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers
    $email_headers = "From: $name <$email>";

    // Send the email
    // Note: mail() might not work in all environments, but this is the standard way.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        display_message("Terima kasih! Pesan Anda telah terkirim.", "success");
    } else {
        http_response_code(500);
        display_message("Maaf, terjadi kesalahan dan kami tidak dapat mengirimkan pesan Anda.", "error");
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code
    http_response_code(403);
    display_message("Ada masalah dengan pengiriman Anda, silakan coba lagi.", "error");
}

/**
 * Display a styled message to the user
 */
function display_message($msg, $type) {
    $color = ($type == "success") ? "#6c5ce7" : "#ff7675";
    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Status Pengiriman - Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f9f9f9;
            }
            .message-box {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            h2 { color: $color; margin-bottom: 1rem; }
            p { color: #636e72; margin-bottom: 2rem; }
            .button {
                background-color: #6c5ce7;
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: 0.3s;
            }
            .button:hover { background-color: #5b4cc4; }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <h2>" . htmlspecialchars($msg) . "</h2>
            <a href='../index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
}
?>
