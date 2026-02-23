<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles form submissions from the contact section.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "error";
        $message_text = "Mohon isi formulir dengan benar.";
    } else {
        // In a real scenario, we would send an email here using mail() or a library
        // For this demo, we'll just simulate a successful submission
        $status = "success";
        $message_text = "Terima kasih, $name! Pesan Anda telah kami terima. Kami akan segera menghubungi Anda melalui $email.";
    }
} else {
    // Not a POST request
    http_response_code(403);
    $status = "error";
    $message_text = "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman - Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            color: #2d3436;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 500px;
            text-align: center;
        }
        h2 {
            color: <?php echo ($status == "success") ? "#6c5ce7" : "#e74c3c"; ?>;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #6c5ce7;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #5849c4;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo ($status == "success") ? "Pesan Terkirim!" : "Ups!"; ?></h2>
        <p><?php echo $message_text; ?></p>
        <a href="index.html" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
