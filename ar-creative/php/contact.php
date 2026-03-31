<?php
/**
 * PHP Contact Form Handler for Ar Creative
 * Version: 1.0
 */

// Simulation mode: Set to true to bypass actual mail delivery (useful for development)
define('SIMULATION_MODE', true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and filter input
    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<h1>Kesalahan Input</h1>";
        echo "<p>Mohon lengkapi form dengan benar.</p>";
        echo "<a href='../index.html#contact'>Kembali ke Form</a>";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject   = "Pesan Baru dari Ar Creative: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send the email (or simulate)
    $success = false;
    if (SIMULATION_MODE) {
        $success = true;
    } else {
        $success = @mail($recipient, $subject, $email_content, $email_headers);
    }

    if ($success) {
        http_response_code(200);
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <title>Pesan Terkirim - Ar Creative</title>
            <style>
                body { font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: hsl(250, 60%, 99%); color: hsl(250, 8%, 15%); }
                .container { text-align: center; padding: 2rem; background: white; border-radius: 1rem; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
                h1 { color: hsl(250, 69%, 61%); }
                a { display: inline-block; margin-top: 1rem; padding: .75rem 1.5rem; background: hsl(250, 69%, 61%); color: white; text-decoration: none; border-radius: .5rem; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>Terima Kasih!</h1>
                <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.</p>
                <a href='../index.html'>Kembali ke Beranda</a>
            </div>
        </body>
        </html>";
    } else {
        http_response_code(500);
        echo "<h1>Oops!</h1>";
        echo "<p>Maaf, terjadi kesalahan saat mengirim pesan Anda. Silakan coba lagi nanti.</p>";
        echo "<a href='../index.html#contact'>Kembali ke Form</a>";
    }

} else {
    // Not a POST request
    http_response_code(403);
    echo "<h1>Akses Dilarang</h1>";
    echo "<p>Metode pengiriman tidak diizinkan.</p>";
}
?>
