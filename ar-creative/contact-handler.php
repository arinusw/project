<?php
/**
 * Ar Creative Contact Form Handler
 * Handles form submission and provides a feedback UI.
 */

// Simulation mode: Set to true to skip actual mail delivery (useful for development)
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject_input = strip_tags(trim($_POST["subject"]));
    $message_content = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message_content) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "error";
        $title = "Oops!";
        $message = "Mohon lengkapi formulir dengan benar.";
    } else {
        // Recipient email
        $recipient = "infostudio.ari@gmail.com";
        $subject = "Pesan Baru dari Ar Creative: $subject_input";

        // Email content
        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$message_content\n";

        // Email headers
        $email_headers = "From: $name <$email>";

        // Send email (or simulate)
        if ($simulation_mode) {
            $success = true;
        } else {
            $success = @mail($recipient, $subject, $email_content, $email_headers);
        }

        if ($success) {
            http_response_code(200);
            $status = "success";
            $title = "Terima Kasih!";
            $message = "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.";
        } else {
            http_response_code(500);
            $status = "error";
            $title = "Maaf!";
            $message = "Terjadi kesalahan teknis saat mengirim pesan. Silakan coba lagi nanti.";
        }
    }
} else {
    // Not a POST request
    http_response_code(403);
    $status = "error";
    $title = "Akses Ditolak";
    $message = "Metode pengiriman tidak valid.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman | Ar Creative</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: hsl(250, 60%, 99%);
            color: hsl(250, 8%, 15%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 1.5rem;
        }
        .card {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 1.25rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: inline-block;
        }
        .icon--success { color: hsl(250, 69%, 61%); }
        .icon--error { color: #e74c3c; }
        h1 { font-size: 1.5rem; margin-bottom: 1rem; }
        p { font-size: 1rem; color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
        .button {
            display: inline-block;
            background-color: hsl(250, 69%, 61%);
            color: #fff;
            padding: 1rem 2rem;
            border-radius: .75rem;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }
        .button:hover { background-color: hsl(250, 57%, 53%); }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon <?php echo ($status == 'success') ? 'icon--success' : 'icon--error'; ?>">
            <i class="<?php echo ($status == 'success') ? 'ri-checkbox-circle-line' : 'ri-error-warning-line'; ?>"></i>
        </div>
        <h1><?php echo $title; ?></h1>
        <p><?php echo $message; ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
