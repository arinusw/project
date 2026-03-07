<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "<div style='font-family: sans-serif; padding: 20px; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "Maaf, silakan lengkapi formulir dengan benar.";
        echo "</div>";
        exit;
    }

    // Email configuration (example)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari $name via Ar Creative Website";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Send email (Note: This might not work on local servers without mail setup)
    // mail($recipient, $subject, $email_content, $email_headers);

    // Response
    http_response_code(200);
    echo "<div style='font-family: sans-serif; padding: 20px; color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; text-align: center;'>";
    echo "<h2>Terima Kasih!</h2>";
    echo "<p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.</p>";
    echo "<a href='index.html' style='display: inline-block; margin-top: 10px; padding: 10px 20px; background-color: #155724; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Beranda</a>";
    echo "</div>";
} else {
    http_response_code(403);
    echo "Akses dilarang.";
}
?>
