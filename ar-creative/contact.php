<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle error
        http_response_code(400);
        echo "<div style='font-family: sans-serif; padding: 20px; text-align: center;'>";
        echo "<h2>Terjadi Kesalahan</h2>";
        echo "<p>Silakan isi formulir dengan benar.</p>";
        echo "<a href='index.html'>Kembali</a>";
        echo "</div>";
        exit;
    }

    // Prepare email (Note: This is a placeholder, as mail() might not work in all environments)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Success response (styled in Bahasa Indonesia)
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Pesan Terkirim | Ar Creative</title>
        <style>
            body { font-family: 'Poppins', sans-serif; background-color: #f9f9ff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .container { background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; max-width: 400px; }
            h2 { color: #6e57e0; }
            p { color: #6d6a7c; margin-bottom: 2rem; }
            .button { background-color: #6e57e0; color: #fff; padding: 1rem 2rem; border-radius: .5rem; text-decoration: none; display: inline-block; font-weight: 500; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Terima Kasih!</h2>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
            <a href='index.html' class='button'>Kembali ke Beranda</a>
        </div>
    </body>
    </html>";

} else {
    // Not a POST request
    header("Location: index.html");
    exit;
}
?>
