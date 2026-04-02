<?php
/**
 * Contact Form Handler for Ar Creative
 * Version: 1.0
 */

// Simulation mode for environments without a configured mail server
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and filter input
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Maaf, formulir tidak lengkap. Silakan coba lagi.";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";

    // Email subject
    $subject = "Pesan Baru dari $name (Kontak Ar Creative)";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    if ($simulation_mode) {
        // In simulation mode, we always return success for UI verification
        render_success_page($name);
    } else {
        // Send the email
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            render_success_page($name);
        } else {
            http_response_code(500);
            echo "Ups! Sesuatu yang salah terjadi dan kami tidak dapat mengirim pesan Anda.";
        }
    }

} else {
    // Not a POST request
    http_response_code(403);
    echo "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}

/**
 * Render a simple success page
 */
function render_success_page($name) {
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
                background-color: #f9f9ff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                color: #2b2b35;
            }
            .card {
                background: white;
                padding: 2.5rem;
                border-radius: 1rem;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            h1 { color: #6e57e0; }
            p { margin-bottom: 2rem; }
            .btn {
                background: #6e57e0;
                color: white;
                padding: .75rem 1.5rem;
                text-decoration: none;
                border-radius: .5rem;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Terima Kasih!</h1>
            <p>Halo <strong><?php echo htmlspecialchars($name); ?></strong>, pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href="index.html" class="btn">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
}
?>
