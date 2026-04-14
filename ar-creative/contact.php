<?php
/**
 * Ar Creative Contact Form Handler
 * Handles contact form submissions and provides a simulation mode for development.
 */

// Simulation mode: set to true to bypass actual mail delivery
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Maaf, ada masalah dengan pengiriman Anda. Harap lengkapi formulir dan coba lagi.";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari $name melalui Website Ar Creative";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    if ($simulation_mode) {
        // In simulation mode, we always return success with a themed response
        render_success_page($name);
    } else {
        // Send the email.
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            render_success_page($name);
        } else {
            http_response_code(500);
            echo "Ups! Terjadi kesalahan dan kami tidak dapat mengirimkan pesan Anda.";
        }
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}

/**
 * Renders a simple and styled success page in Bahasa Indonesia
 */
function render_success_page($name) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
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
            text-align: center;
        }
        .container {
            background: #fff;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .icon {
            font-size: 4rem;
            color: hsl(250, 69%, 61%);
            margin-bottom: 1rem;
        }
        h1 { margin-bottom: 1rem; }
        p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
        .button {
            display: inline-block;
            background-color: hsl(250, 69%, 61%);
            color: #fff;
            padding: 1rem 2rem;
            border-radius: .5rem;
            text-decoration: none;
            font-weight: 500;
            transition: .3s;
        }
        .button:hover { background-color: hsl(250, 57%, 53%); }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class="ri-checkbox-circle-line"></i></div>
        <h1>Terima Kasih, <?php echo htmlspecialchars($name); ?>!</h1>
        <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
<?php
}
?>
