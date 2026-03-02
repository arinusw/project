<?php
/**
 * Ar Creative - Contact Form Handler
 * This script handles the contact form submission.
 * It sanitizes inputs and provides a professional response.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $response_message = "Harap isi semua kolom dengan benar.";
        $status = "error";
    } else {
        // In a real-world scenario, you would send an email here
        // Example: mail("infostudio.ari@gmail.com", "Pesan Baru dari $name", $message, "From: $email");

        http_response_code(200);
        $response_message = "Terima kasih, $name! Pesan Anda telah kami terima. Kami akan menghubungi Anda segera.";
        $status = "success";
    }
} else {
    // Not a POST request
    http_response_code(403);
    $response_message = "Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.";
    $status = "error";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 450px;
            width: 90%;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .success { color: #6c5ce7; }
        .error { color: #ff7675; }
        h1 { font-size: 1.5rem; margin-bottom: 1rem; }
        p { margin-bottom: 2rem; line-height: 1.6; }
        .button {
            display: inline-block;
            background-color: #6c5ce7;
            color: #fff;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .button:hover { background-color: #5b4cc4; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon <?php echo $status; ?>">
            <?php echo ($status == "success") ? "✓" : "⚠"; ?>
        </div>
        <h1><?php echo ($status == "success") ? "Pesan Terkirim!" : "Terjadi Kesalahan"; ?></h1>
        <p><?php echo $response_message; ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
