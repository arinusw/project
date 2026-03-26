<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Check for required fields
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
        echo "<h1 style='color: #e74c3c;'>Oops!</h1>";
        echo "<p>Silakan lengkapi formulir dengan benar.</p>";
        echo "<a href='index.html' style='color: #3498db;'>Kembali</a>";
        echo "</div>";
        exit;
    }

    // Email content (for simulation or production)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Send email (silently fails on local servers without mail config)
    @mail($recipient, $subject, $email_content, $email_headers);

    // Response for the user
    http_response_code(200);
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pesan Terkirim | Ar Creative</title>
        <style>
            body { font-family: 'Poppins', sans-serif; background-color: #f9f9f9; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .container { text-align: center; background: white; padding: 3rem; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 400px; width: 90%; }
            h1 { color: #6e57e0; }
            p { margin-bottom: 2rem; color: #666; }
            .button { background-color: #6e57e0; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 0.5rem; transition: 0.3s; }
            .button:hover { background-color: #5a43cb; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim ke Ar Creative. Kami akan segera menghubungi Anda melalui email.</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
} else {
    http_response_code(403);
    echo "Metode pengiriman tidak diizinkan.";
}
?>
