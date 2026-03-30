<?php
/**
 * Contact Form Handler for Ar Creative
 * Developed for PHP 7.4+
 */

// Simulation mode to verify UI flow without triggering real mail delivery
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Ops! Terjadi masalah dengan pengiriman Anda. Silakan lengkapi formulir dan coba lagi.";
        exit;
    }

    // Set the recipient email address.
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan baru dari $name via Ar Creative Website";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (!$simulation_mode) {
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (success) response code.
            http_response_code(200);
            $success = true;
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            $success = false;
        }
    } else {
        // Simulation success
        http_response_code(200);
        $success = true;
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Terkirim - Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        h1 { color: hsl(250, 69%, 61%); }
        p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
        .button {
            background-color: hsl(250, 69%, 61%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: 0.3s;
        }
        .button:hover { background-color: hsl(250, 57%, 53%); }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
        <?php else: ?>
            <h1>Ups!</h1>
            <p>Maaf, terjadi kesalahan saat mengirim pesan Anda. Silakan coba lagi nanti.</p>
        <?php endif; ?>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
