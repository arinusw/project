<?php
/**
 * Ar Creative - Contact Form Handler
 * PHP version 7.4 or higher
 */

// Simulation mode for development/CI environments
define('SIMULATION_MODE', true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        show_response("Ups! Terjadi masalah dengan kiriman Anda. Silakan lengkapi formulir dan coba lagi.", "error");
        exit;
    }

    // Set the recipient email address.
    $recipient = "infostudio.ari@gmail.com";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    if (SIMULATION_MODE) {
        // In simulation mode, we just pretend it worked.
        http_response_code(200);
        show_response("Terima kasih! Pesan Anda telah terkirim (Mode Simulasi).", "success");
    } else {
        // Send the email.
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            show_response("Terima kasih! Pesan Anda telah terkirim.", "success");
        } else {
            http_response_code(500);
            show_response("Maaf! Terjadi kesalahan dan pesan Anda tidak dapat dikirim.", "error");
        }
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    show_response("Ada masalah dengan kiriman Anda, silakan coba lagi.", "error");
}

/**
 * Function to show a styled HTML response
 */
function show_response($message, $type) {
    $title = ($type == "success") ? "Berhasil!" : "Terjadi Kesalahan";
    $color = ($type == "success") ? "hsl(250, 69%, 61%)" : "#e74c3c";

    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$title - Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: hsl(250, 60%, 99%);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .card {
                background: #fff;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                width: 90%;
            }
            h1 { color: $color; margin-bottom: 1rem; }
            p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
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
        <div class='card'>
            <h1>$title</h1>
            <p>$message</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
}
?>
