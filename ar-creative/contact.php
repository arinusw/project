<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles form submissions and provides a simulation mode for development/verification.
 */

// Simulation mode for environments without a mail server
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Basic validation
    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Ops! Ada masalah dengan pengiriman Anda. Silakan lengkapi formulir dan coba lagi.";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    if ($simulation_mode) {
        // In simulation mode, always return success
        $success = true;
    } else {
        // Send the email
        $success = @mail($recipient, $subject, $email_content, $email_headers);
    }

    if ($success) {
        // Set a 200 (ok) response code.
        http_response_code(200);
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Terima Kasih | Ar Creative</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    background-color: hsl(250, 60%, 99%);
                    color: hsl(250, 8%, 15%);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .container {
                    text-align: center;
                    background: white;
                    padding: 3rem;
                    border-radius: 1.5rem;
                    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
                    max-width: 500px;
                }
                h1 { color: hsl(250, 69%, 61%); }
                p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
                .button {
                    display: inline-block;
                    background-color: hsl(250, 69%, 61%);
                    color: white;
                    padding: 1rem 2rem;
                    border-radius: .5rem;
                    text-decoration: none;
                    transition: .3s;
                }
                .button:hover { background-color: hsl(250, 57%, 53%); }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Terima Kasih!</h1>
                <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
                <a href="index.html" class="button">Kembali ke Beranda</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Maaf! Sesuatu yang salah terjadi dan kami tidak dapat mengirim pesan Anda.";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
