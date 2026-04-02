<?php
/**
 * Contact Form Handler for Ar Creative
 * Version 1.0.0
 */

// Simulation mode to verify the UI flow without triggering mail delivery
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<h1>Kesalahan</h1>";
        echo "<p>Mohon isi semua kolom formulir dengan benar.</p>";
        echo "<a href='index.html'>Kembali ke formulir</a>";
        exit;
    }

    // Email configuration
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari Ar Creative Landing Page: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Send email (or simulate)
    $success = false;
    if ($simulation_mode) {
        $success = true;
    } else {
        // Use @ to suppress errors on local servers without mail config
        $success = @mail($recipient, $subject, $email_content, $email_headers);
    }

    // Response
    if ($success) {
        http_response_code(200);
        echo "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Pesan Terkirim | Ar Creative</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
            <style>
                body { font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f9f9ff; color: #1a1a2e; }
                .card { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
                h1 { color: #6e57e0; }
                .button { display: inline-block; margin-top: 1.5rem; padding: 0.75rem 1.5rem; background: #6e57e0; color: white; text-decoration: none; border-radius: 0.5rem; transition: 0.3s; }
                .button:hover { background: #5a43cb; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h1>Pesan Terkirim!</h1>
                <p>Terima kasih, <strong>$name</strong>. Kami telah menerima pesan Anda dan akan segera menghubungi Anda kembali.</p>
                <a href='index.html' class='button'>Kembali ke Beranda</a>
            </div>
        </body>
        </html>";
    } else {
        http_response_code(500);
        echo "<h1>Maaf!</h1>";
        echo "<p>Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.</p>";
        echo "<a href='index.html'>Kembali</a>";
    }
} else {
    http_response_code(403);
    echo "<h1>Akses Dilarang</h1>";
    echo "<p>Ada masalah dengan pengiriman Anda, silakan coba lagi.</p>";
    echo "<a href='index.html'>Kembali</a>";
}
?>
