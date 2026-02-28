<?php
/*
    Ar Creative Contact Handler
    Author: Jules
    Language: PHP
    Comments: English
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "Oops! Terjadi kesalahan dalam pengiriman pesan. Silakan coba lagi.";
    } else {
        // Success response
        http_response_code(200);
        $status = "Terima kasih! Pesan Anda telah berhasil terkirim. Kami akan segera menghubungi Anda.";
    }
} else {
    // Not a POST request
    http_response_code(403);
    $status = "Terjadi masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Terkirim | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9ff;
            color: #2b2b2b;
            text-align: center;
        }
        .container {
            max-width: 500px;
            padding: 2rem;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 { color: #6c5ce7; margin-bottom: 1rem; }
        p { margin-bottom: 2rem; }
        .btn {
            display: inline-block;
            background-color: #6c5ce7;
            color: #fff;
            padding: .75rem 1.5rem;
            border-radius: .5rem;
            text-decoration: none;
            transition: .3s;
        }
        .btn:hover { background-color: #5a4ccb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Status Pengiriman</h2>
        <p><?php echo htmlspecialchars($status); ?></p>
        <a href="index.html" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
