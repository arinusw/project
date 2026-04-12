<?php
/**
 * Ar Creative - Contact Form Handler
 * Verifies and handles contact form submissions.
 * Includes a simulation mode for development/verification.
 */

// Simulation mode: set to true to bypass actual mail sending
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Check if data is valid
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        show_response("error", "Ups! Ada masalah dengan pengiriman Anda. Harap lengkapi formulir dan coba lagi.");
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name via Website Ar Creative";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    if ($simulation_mode) {
        // Simulation success
        show_response("success", "Terima kasih! Pesan Anda telah terkirim (Mode Simulasi). Kami akan segera menghubungi Anda.");
    } else {
        // Attempt to send email
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            show_response("success", "Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.");
        } else {
            http_response_code(500);
            show_response("error", "Ups! Terjadi kesalahan dan kami tidak dapat mengirimkan pesan Anda.");
        }
    }
} else {
    // Not a POST request
    http_response_code(403);
    show_response("error", "Ada masalah dengan pengiriman Anda, silakan coba lagi.");
}

/**
 * Helper function to show a styled response page
 */
function show_response($type, $message) {
    $title = ($type == "success") ? "Berhasil!" : "Kesalahan";
    $color = ($type == "success") ? "#6c63ff" : "#ff4d4d";
    $icon = ($type == "success") ? "ri-checkbox-circle-line" : "ri-error-warning-line";

    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$title - Ar Creative</title>
        <link href='https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #0f0b1e;
                color: #fff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                text-align: center;
            }
            .card {
                background-color: #1a162d;
                padding: 2.5rem;
                border-radius: 1rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                max-width: 400px;
                width: 90%;
            }
            .icon {
                font-size: 4rem;
                color: $color;
                margin-bottom: 1rem;
            }
            h1 { margin-bottom: 1rem; }
            p { color: #ccc; margin-bottom: 2rem; }
            .button {
                display: inline-block;
                background-color: #6c63ff;
                color: #fff;
                padding: .75rem 1.5rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 500;
                transition: .3s;
            }
            .button:hover { background-color: #5a52d4; }
        </style>
    </head>
    <body>
        <div class='card'>
            <i class='$icon icon'></i>
            <h1>$title</h1>
            <p>$message</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
}
?>
