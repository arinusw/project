<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Mohon lengkapi form dengan benar.";
        exit;
    }

    // Recipient email (replace with your actual email)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "<h1>Terima Kasih!</h1><p>Pesan Anda telah berhasil dikirim.</p><a href='index.html'>Kembali ke Beranda</a>";
    } else {
        http_response_code(500);
        echo "Oops! Terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti.";
    }
} else {
    http_response_code(403);
    echo "Metode pengiriman tidak diizinkan.";
}
?>
