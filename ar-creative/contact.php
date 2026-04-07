<?php
/**
 * Contact Form Handler for Ar Creative
 * Developed for PHP 7.4+
 */

// Simulation mode to verify UI flow without triggering mail delivery
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Check data
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        show_response("Ups! Terjadi kesalahan.", "Pastikan semua kolom diisi dengan benar dan coba lagi.", "error");
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Ar Creative: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    if ($simulation_mode) {
        // In simulation mode, always succeed
        show_response("Terima Kasih!", "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.", "success");
    } else {
        // Send email
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            show_response("Terima Kasih!", "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.", "success");
        } else {
            http_response_code(500);
            show_response("Ups!", "Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.", "error");
        }
    }
} else {
    // Not a POST request
    http_response_code(403);
    show_response("Akses Ditolak", "Anda tidak memiliki izin untuk mengakses halaman ini secara langsung.", "error");
}

/**
 * Helper function to display a styled response
 */
function show_response($title, $message, $type) {
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
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: hsl(250, 60%, 99%);
                color: hsl(250, 8%, 15%);
                text-align: center;
            }
            .card {
                background: white;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                max-width: 400px;
            }
            h1 { color: $color; margin-bottom: 1rem; }
            p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
            .button {
                display: inline-block;
                background-color: hsl(250, 69%, 61%);
                color: white;
                padding: 1rem 2rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 600;
                transition: .3s;
            }
            .button:hover { background-color: hsl(250, 69%, 57%); }
        </style>
    </head>
    <body>
        <div class='card'>
            <h1>$title</h1>
            <p>$message</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    ";
}
?>
