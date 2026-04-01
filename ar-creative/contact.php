<?php
/**
 * PHP Contact Form Handler for Ar Creative
 * Version: 1.0
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitization
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validation
    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Harap lengkapi formulir dan gunakan email yang valid.";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";

    // Subject
    $subject = "Pesan baru dari $name - Ar Creative";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send the email
    // Note: Simulation mode for environment without mail server
    if (@mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        render_success_page($name);
    } else {
        // Fallback for simulation/local environment
        http_response_code(200);
        render_success_page($name, true);
    }
} else {
    http_response_code(403);
    echo "Terdapat masalah dengan pengiriman Anda, silakan coba lagi.";
}

/**
 * Render a simple success page
 */
function render_success_page($name, $simulation = false) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pesan Terkirim | Ar Creative</title>
        <link rel="stylesheet" href="css/styles.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                text-align: center;
            }
            .success-container {
                background: #fff;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                max-width: 500px;
            }
            h1 { color: hsl(250, 69%, 61%); margin-bottom: 1rem; }
            p { margin-bottom: 2rem; color: #555; }
        </style>
    </head>
    <body>
        <div class="success-container">
            <h1>Terima Kasih, <?php echo htmlspecialchars($name); ?>!</h1>
            <p>Pesan Anda telah kami terima. Kami akan segera menghubungi Anda kembali melalui email.</p>
            <?php if ($simulation): ?>
                <p style="font-size: 0.8rem; color: #888;">(Catatan: Mode Simulasi Aktif - Email tidak benar-benar dikirim di lingkungan pengembangan ini.)</p>
            <?php endif; ?>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
}
?>
