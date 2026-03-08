<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Harap lengkapi semua bidang dengan benar.";
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";

    // Email subject
    $subject = "Kontak Baru dari Ar Creative: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Response message styling
    echo "<div style='font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f9f9ff;'>";
    echo "<h2 style='color: #6c63ff;'>Terima Kasih!</h2>";
    echo "<p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>";
    echo "<a href='index.html' style='display: inline-block; padding: 10px 20px; background-color: #6c63ff; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Kembali ke Beranda</a>";
    echo "</div>";

    // Note: In a real server environment, you would use mail() function
    // @mail($recipient, $subject, $email_content, $email_headers);
} else {
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
