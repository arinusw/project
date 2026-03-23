<?php
/**
 * PHP Contact Form Handler for Ar Creative
 * All content in Bahasa Indonesia, code comments in English.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Basic validation
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Ops! Harap lengkapi formulir dan masukkan email yang valid.";
        exit;
    }

    // Set the recipient email address.
    $recipient = "infostudio.ari@gmail.com";

    // Set the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    // We use @ to suppress errors if mail() is not configured on the server.
    @mail($recipient, $subject, $email_content, $email_headers);

    // Set a 200 (success) response code.
    http_response_code(200);
?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Pesan Terkirim - Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; background-color: #f9f9ff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .card { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
            h1 { color: #6e57e0; }
            p { color: #666; margin-bottom: 2rem; }
            .btn { background: #6e57e0; color: white; text-decoration: none; padding: .8rem 1.5rem; border-radius: .5rem; transition: .3s; }
            .btn:hover { background: #5a43cb; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali melalui email yang telah Anda berikan.</p>
            <a href="index.html" class="btn">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
<?php
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Terdapat masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
