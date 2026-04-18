<?php
/**
 * PHP Contact Form Handler for Ar Creative
 * Version 1.0
 */

// Simulation mode for development environments
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Terdapat kesalahan pada input Anda. Silakan coba lagi.";
        exit;
    }

    // Recipient
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari: $name (via Ar Creative Website)";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email (using @ to suppress errors in non-mail environments)
    if (!$simulation_mode) {
        $mail_success = @mail($recipient, $subject, $email_content, $email_headers);
    } else {
        $mail_success = true; // Simulate success
    }
} else {
    // Not a POST request
    header("Location: index.html");
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
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --first-color: hsl(250, 69%, 61%);
            --title-color: hsl(250, 8%, 15%);
            --text-color: hsl(250, 8%, 45%);
            --body-color: hsl(250, 60%, 98%);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background-color: #fff;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .icon {
            font-size: 4rem;
            color: var(--first-color);
            margin-bottom: 1rem;
        }
        h1 {
            color: var(--title-color);
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: var(--first-color);
            color: #fff;
            padding: 1rem 2rem;
            border-radius: .5rem;
            text-decoration: none;
            transition: .3s;
        }
        .button:hover {
            opacity: .9;
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="ri-checkbox-circle-line icon"></i>
        <?php if ($mail_success): ?>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
        <?php else: ?>
            <h1>Maaf!</h1>
            <p>Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti atau hubungi kami via WhatsApp.</p>
        <?php endif; ?>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
