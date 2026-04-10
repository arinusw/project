<?php
/**
 * Ar Creative Contact Form Handler
 * Handles form submission, sanitization, and provides a styled response.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle error
        http_response_code(400);
        show_response("Ups! Terjadi kesalahan.", "Pastikan semua kolom diisi dengan benar dan coba lagi.", "error");
        exit;
    }

    // Email configuration (Simulated)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari $name (Ar Creative)";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Attempt to send (Simulated/Muted for dev)
    // In production, use: mail($recipient, $subject, $email_content, $email_headers)
    @mail($recipient, $subject, $email_content, $email_headers);

    // Success response
    show_response("Terima Kasih!", "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.", "success");
} else {
    // Not a POST request
    http_response_code(403);
    show_response("Akses Ditolak", "Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.", "error");
}

/**
 * Helper function to display a styled response page
 */
function show_response($title, $message, $type) {
    $hue = 250;
    $color = ($type == "success") ? "hsl($hue, 69%, 61%)" : "#e74c3c";
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
                background-color: hsl(<?php echo $hue; ?>, 60%, 99%);
                color: hsl(<?php echo $hue; ?>, 8%, 15%);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background: #fff;
                padding: 3rem;
                border-radius: 1rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                text-align: center;
                max-width: 500px;
                width: 90%;
            }
            h1 { color: <?php echo $color; ?>; margin-bottom: 1rem; }
            p { color: hsl(<?php echo $hue; ?>, 8%, 45%); margin-bottom: 2rem; }
            .button {
                display: inline-block;
                background-color: hsl(<?php echo $hue; ?>, 69%, 61%);
                color: #fff;
                padding: .75rem 1.5rem;
                border-radius: .5rem;
                text-decoration: none;
                font-weight: 500;
                transition: .3s;
            }
            .button:hover { background-color: hsl(<?php echo $hue; ?>, 57%, 53%); }
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
