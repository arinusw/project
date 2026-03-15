<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Basic validation
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Silakan lengkapi formulir dan coba lagi.";
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer here.
    // Since this is a static-first portfolio, we'll just simulate a success response.

    // Set a 200 (OK) response code.
    http_response_code(200);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pesan Terkirim | Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; background-color: #f9f9ff; color: #1a1a1a; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .card { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
            h1 { color: #6e57e0; }
            p { color: #6d6a7c; margin-bottom: 2rem; }
            .button { background-color: #6e57e0; color: white; padding: .75rem 1.5rem; border-radius: .5rem; text-decoration: none; font-weight: 600; transition: .3s; }
            .button:hover { background-color: #5a43cb; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
} else {
    // Not a POST request
    http_response_code(403);
    echo "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
