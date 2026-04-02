<?php
/**
 * Ar Creative Contact Form Handler
 * Simply handles form submission and displays a success message.
 * Simulation mode: No real email is sent.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Logic to simulate mail sending
    $success = true;

    // Display result page
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pesan Terkirim — Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f9f9f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                color: #333;
            }
            .message-container {
                background: #fff;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            h1 { color: #6e57e0; }
            p { margin-bottom: 2rem; }
            .button {
                background-color: #6e57e0;
                color: #fff;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: 0.3s;
            }
            .button:hover { background-color: #5a43cb; }
        </style>
    </head>
    <body>
        <div class="message-container">
            <?php if ($success): ?>
                <h1>Terima Kasih!</h1>
                <p>Halo <strong><?php echo htmlspecialchars($name); ?></strong>, pesan Anda telah kami terima. Kami akan segera menghubungi Anda melalui email <strong><?php echo htmlspecialchars($email); ?></strong>.</p>
            <?php else: ?>
                <h1>Oops!</h1>
                <p>Maaf, terjadi kesalahan saat mengirim pesan Anda. Silakan coba lagi nanti.</p>
            <?php endif; ?>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
} else {
    // If not a POST request, redirect back to home
    header("Location: index.html");
    exit;
}
?>
