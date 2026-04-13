<?php
/**
 * Ar Creative - Contact Form Handler
 * Verifies form data and provides a styled response.
 * Includes a simulation mode for development/verification.
 */

// Simulation mode: set to true to bypass actual mailing and always show success
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validate inputs
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle validation error
        http_response_code(400);
        show_response("Ups! Terjadi kesalahan.", "Harap lengkapi formulir dengan benar dan coba lagi.", "error");
        exit;
    }

    // Recipient email (using the one from website identity)
    $recipient = "infostudio.ari@gmail.com";

    // Email content
    $subject = "Pesan Baru dari Ar Creative Portfolio: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email or simulate success
    if ($simulation_mode) {
        http_response_code(200);
        show_response("Pesan Terkirim!", "Terima kasih telah menghubungi Ar Creative. Kami akan segera membalas pesan Anda.", "success");
    } else {
        // Send the email
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            show_response("Pesan Terkirim!", "Terima kasih telah menghubungi Ar Creative. Kami akan segera membalas pesan Anda.", "success");
        } else {
            http_response_code(500);
            show_response("Ups! Terjadi kesalahan.", "Maaf, terjadi masalah saat mengirim pesan Anda. Silakan coba lagi nanti.", "error");
        }
    }

} else {
    // Not a POST request
    http_response_code(403);
    show_response("Akses Ditolak", "Maaf, Anda tidak memiliki akses ke halaman ini.", "error");
}

/**
 * Displays a styled HTML response
 */
function show_response($title, $message, $type) {
    $color = ($type === "success") ? "hsl(250, 69%, 61%)" : "#e74c3c";
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?> | Ar Creative</title>
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
                text-align: center;
            }
            .container {
                background: #fff;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                max-width: 500px;
                width: 90%;
            }
            h1 {
                color: <?php echo $color; ?>;
                margin-bottom: 1rem;
            }
            p {
                color: hsl(250, 8%, 45%);
                margin-bottom: 2rem;
                line-height: 1.6;
            }
            .button {
                display: inline-block;
                background-color: hsl(250, 69%, 61%);
                color: #fff;
                padding: 1rem 2rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 600;
                transition: .3s;
            }
            .button:hover {
                background-color: hsl(250, 57%, 53%);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1><?php echo $title; ?></h1>
            <p><?php echo $message; ?></p>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
}
?>
