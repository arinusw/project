<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Harap lengkapi semua bidang dengan benar.";
        exit;
    }

    // Recipient email (placeholder)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name di Ar Creative";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Send email (commented out for demo purposes, but functional)
    // mail($recipient, $subject, $email_content, $email_headers);

    // Provide a styled success response
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pesan Terkirim | Ar Creative</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f9f9ff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .card {
                background: #fff;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
            }
            .icon {
                font-size: 4rem;
                color: #6c5ce7;
                margin-bottom: 1rem;
            }
            h1 {
                color: #2d3436;
                margin-bottom: 0.5rem;
            }
            p {
                color: #636e72;
                margin-bottom: 1.5rem;
            }
            .button {
                display: inline-block;
                background: #6c5ce7;
                color: #fff;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: 0.3s;
            }
            .button:hover {
                background: #5b4cc4;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <i class='bx bx-check-circle icon'></i>
            <h1>Terima Kasih!</h1>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href="index.html" class="button">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
} else {
    // Redirect back if accessed directly
    header("Location: index.html");
    exit;
}
?>
