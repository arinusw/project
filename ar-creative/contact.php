<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "<div style='font-family: Poppins, sans-serif; padding: 2rem; text-align: center;'>";
        echo "<h1 style='color: #ff4d4d;'>Ups!</h1>";
        echo "<p>Mohon lengkapi formulir dan coba lagi.</p>";
        echo "<a href='index.html' style='color: #6c5ce7;'>Kembali ke Beranda</a>";
        echo "</div>";
        exit;
    }

    // Update this to your desired email address.
    $recipient = "infostudio.ari@gmail.com";

    // Set the email subject.
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";

    // Build the email content.
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    // NOTE: mail() might not work in all local environments without proper configuration
    // but this is the standard way to do it in PHP for simple forms.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (ok) response code.
        http_response_code(200);
        echo "<div style='font-family: Poppins, sans-serif; padding: 2rem; text-align: center; color: #333;'>";
        echo "<h1 style='color: #6c5ce7;'>Terima Kasih!</h1>";
        echo "<p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.</p>";
        echo "<a href='index.html' style='display: inline-block; margin-top: 1rem; padding: .75rem 1.5rem; background-color: #6c5ce7; color: #fff; text-decoration: none; border-radius: .5rem;'>Kembali ke Beranda</a>";
        echo "</div>";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "<div style='font-family: Poppins, sans-serif; padding: 2rem; text-align: center;'>";
        echo "<h1 style='color: #ff4d4d;'>Ups!</h1>";
        echo "<p>Maaf, terjadi kesalahan dan pesan Anda tidak dapat dikirim.</p>";
        echo "<a href='index.html' style='color: #6c5ce7;'>Coba lagi</a>";
        echo "</div>";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
