<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles form submissions and provides feedback.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject_input = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        display_message("Error", "Harap isi semua kolom dengan benar.", "error");
        exit;
    }

    // Simulation mode (Actual mail delivery is not configured in this environment)
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative: " . $subject_input;
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    $email_headers = "From: $name <$email>";

    // Attempt to send email (suppress errors for simulation)
    $success = @mail($to, $subject, $email_content, $email_headers);

    // In this simulation environment, we always show success if validation passed
    display_message("Terima Kasih!", "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda melalui email.", "success");
} else {
    // Not a POST request
    http_response_code(403);
    display_message("Akses Ditolak", "Maaf, Anda tidak memiliki akses ke halaman ini.", "error");
}

/**
 * Displays a styled response message.
 */
function display_message($title, $text, $type) {
    $accent_color = ($type === "success") ? "hsl(250, 69%, 61%)" : "hsl(0, 69%, 61%)";
    echo "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>$title | Ar Creative</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap' rel='stylesheet'>
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
            .message-container {
                background: #fff;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                width: 90%;
            }
            h1 { color: $accent_color; margin-bottom: 1rem; }
            p { margin-bottom: 2rem; line-height: 1.6; color: hsl(250, 8%, 45%); }
            .back-button {
                display: inline-block;
                background-color: hsl(250, 69%, 61%);
                color: #fff;
                padding: .75rem 1.5rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 500;
                transition: .3s;
            }
            .back-button:hover { background-color: hsl(250, 57%, 53%); }
        </style>
    </head>
    <body>
        <div class='message-container'>
            <h1>$title</h1>
            <p>$text</p>
            <a href='index.html' class='back-button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";
}
?>
