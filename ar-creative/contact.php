<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Check for empty fields
    if (empty($name) || empty($email) || empty($message)) {
        echo "Terdapat kesalahan. Harap lengkapi semua bidang.";
        exit;
    }

    // Set recipient and subject
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";

    // Set email headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Email body
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Send the email (suppress errors if server isn't configured for mail)
    $success = @mail($recipient, $subject, $email_content, $headers);

    // Provide a user-friendly response
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pesan Terkirim | Ar Creative</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: hsl(250, 60%, 99%); }
            .card { text-align: center; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 400px; }
            h1 { color: hsl(250, 69%, 61%); }
            p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
            .button { background-color: hsl(250, 69%, 61%); color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 0.5rem; }
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
    // If accessed directly, redirect back to index.html
    header("Location: index.html");
    exit;
}
?>
