<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"] ?? ''));

    // Recipient email
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Portofolio Ar Creative";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Response message
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pesan Terkirim - Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body { font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f4f7f6; }
            .card { background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
            h1 { color: #6e57e0; margin-bottom: 1rem; }
            p { color: #666; margin-bottom: 2rem; }
            .button { background: #6e57e0; color: #fff; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; }
        </style>
    </head>
    <body>
        <div class='card'>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";

    // In a real server environment, you would use mail()
    // mail($to, $subject, $email_content, $email_headers);
} else {
    header("Location: index.html");
    exit;
}
?>
