<?php
/**
 * Contact Form Handler for Ar Creative
 * Version: 1.0
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Basic Validation
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "error";
        $message_text = "Mohon lengkapi formulir dengan benar.";
    } else {
        // In a real scenario, you would use mail() here.
        // For simulation, we'll just assume it's successful.
        $recipient = "infostudio.ari@gmail.com";
        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$message\n";
        $email_headers = "From: $name <$email>";

        // @mail($recipient, $subject, $email_content, $email_headers);

        $status = "success";
        $message_text = "Terima kasih! Pesan Anda telah terkirim.";
    }
} else {
    http_response_code(403);
    $status = "error";
    $message_text = "Terjadi masalah saat mengirim pesan. Silakan coba lagi.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: hsl(250, 60%, 98%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message-card {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .success { color: #2ecc71; }
        .error { color: #e74c3c; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        p { color: #555; margin-bottom: 1.5rem; }
        .button {
            display: inline-block;
            background: hsl(250, 69%, 61%);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: 0.3s;
        }
        .button:hover { background: hsl(250, 57%, 53%); }
    </style>
</head>
<body>
    <div class="message-card">
        <div class="icon <?php echo $status; ?>">
            <?php if($status == "success"): ?>
                <i class="ri-checkbox-circle-line"></i>
            <?php else: ?>
                <i class="ri-error-warning-line"></i>
            <?php endif; ?>
        </div>
        <h1><?php echo ($status == "success") ? "Berhasil!" : "Gagal!"; ?></h1>
        <p><?php echo $message_text; ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
