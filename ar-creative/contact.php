<?php
/**
 * Ar Creative - Contact Form Handler
 * PHP Version 7.4 or higher
 */

// Simulation mode to verify UI flow without actual mail delivery
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        show_response("Oops! Terjadi masalah dengan pengiriman Anda. Harap lengkapi formulir dan coba lagi.", false);
        exit;
    }

    // Set the recipient email address.
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan Baru dari $name (via Ar Creative Website)";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    if ($simulation_mode) {
        // Success response for simulation
        http_response_code(200);
        show_response("Terima kasih! Pesan Anda telah dikirim (Mode Simulasi).", true);
    } else {
        // Send the email.
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (ok) response code.
            http_response_code(200);
            show_response("Terima kasih! Pesan Anda telah terkirim.", true);
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            show_response("Maaf! Terjadi kesalahan dan pesan Anda tidak dapat dikirim.", false);
        }
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    show_response("Ada masalah dengan pengiriman Anda, silakan coba lagi.", false);
}

/**
 * Display a styled HTML response
 */
function show_response($message, $success) {
    $title = $success ? "Berhasil!" : "Gagal!";
    $color = $success ? "hsl(250, 69%, 61%)" : "#e74c3c";
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?> - Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: hsl(250, 60%, 99%);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .response-card {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                width: 90%;
            }
            h1 { color: <?php echo $color; ?>; margin-bottom: 1rem; }
            p { color: hsl(250, 8%, 35%); margin-bottom: 2rem; }
            .back-btn {
                background-color: hsl(250, 69%, 61%);
                color: white;
                padding: .75rem 1.5rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 500;
                transition: .3s;
            }
            .back-btn:hover { background-color: hsl(250, 57%, 53%); }
        </style>
    </head>
    <body>
        <div class="response-card">
            <h1><?php echo $title; ?></h1>
            <p><?php echo $message; ?></p>
            <a href="index.html" class="back-btn">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
}
?>
