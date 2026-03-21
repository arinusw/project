<?php
/**
 * Contact Form Handler for Ar Creative
 *
 * This script processes the contact form submissions.
 * It sanitizes inputs and provides a styled HTML response.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"] ?? ''));

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        display_message("error", "Harap isi semua bidang dengan benar.");
        exit;
    }

    // Email configuration (Placeholders)
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    $email_headers = "From: $name <$email>";

    // In a real environment, mail() would be used here:
    // mail($to, $subject, $email_content, $email_headers);

    display_message("success", "Terima kasih, $name! Pesan Anda telah terkirim.");
} else {
    // Not a POST request
    header("Location: ../index.html");
    exit;
}

/**
 * Function to display a styled message
 */
function display_message($status, $text) {
    $bg_color = ($status == "success") ? "#e3fcef" : "#ffe3e3";
    $text_color = ($status == "success") ? "#006644" : "#bf2600";
    $border_color = ($status == "success") ? "#00875a" : "#de350b";

    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Status Pengiriman - Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body { font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f9f9f9; }
            .message-box { padding: 2rem; border-radius: 1rem; border: 2px solid $border_color; background-color: $bg_color; color: $text_color; text-align: center; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            h1 { font-size: 1.5rem; margin-bottom: 1rem; }
            .btn { display: inline-block; margin-top: 1.5rem; padding: .75rem 1.5rem; background-color: $text_color; color: #fff; text-decoration: none; border-radius: .5rem; font-weight: 600; }
        </style>
    </head>
    <body>
        <div class='message-box'>
            <h1>" . ($status == "success" ? "Berhasil!" : "Gagal!") . "</h1>
            <p>$text</p>
            <a href='../index.html' class='btn'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
}
?>
