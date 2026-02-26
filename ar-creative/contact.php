<?php
/**
 * Contact Form Handler for Ar Creative
 * Minimalist, secure, and professional response.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        show_response("Ups! Terjadi masalah dengan pengiriman Anda. Harap lengkapi formulir dan coba lagi.", "error");
        exit;
    }

    // Recipient email address
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan Baru dari Portofolio Ar Creative oleh $name";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (ok) response code.
        http_response_code(200);
        show_response("Terima kasih! Pesan Anda telah terkirim ke Ar Creative.", "success");
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        show_response("Maaf! Terjadi kesalahan saat mengirim pesan Anda.", "error");
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    show_response("Terjadi masalah dengan pengiriman Anda, silakan coba lagi.", "error");
}

/**
 * Helper function to show a styled response
 */
function show_response($message, $type) {
    $msg_safe = htmlspecialchars($message);
    $color = ($type == "success") ? "#6c5ce7" : "#e74c3c";
    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Status Pengiriman - Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .card { background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 90%; }
            h1 { color: $color; font-size: 1.5rem; margin-bottom: 1rem; }
            p { color: #555; margin-bottom: 2rem; }
            .button { background-color: #6c5ce7; color: #fff; text-decoration: none; padding: .75rem 1.5rem; border-radius: .5rem; font-weight: 600; transition: .3s; }
            .button:hover { background-color: #5a4bcf; }
        </style>
    </head>
    <body>
        <div class='card'>
            <h1>" . ($type == "success" ? "Berhasil!" : "Gagal!") . "</h1>
            <p>$msg_safe</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
}
?>
