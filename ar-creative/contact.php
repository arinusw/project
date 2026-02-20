<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles form submissions and provides feedback to the user.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Silakan lengkapi formulir dengan benar.";
    } else {
        // In a real scenario, you would send an email here
        // mail("infostudio.ari@gmail.com", "Pesan dari: $name", $message, "From: $email");
        $success = "Terima kasih, $name! Pesan Anda telah kami terima.";
    }
} else {
    // Redirect if accessed directly
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f1f2f6;
            color: #2d3436;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }
        h2 { color: #6c5ce7; margin-bottom: 1rem; }
        p { margin-bottom: 2rem; color: #636e72; }
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: #6c5ce7;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: 0.3s;
        }
        .btn:hover { background: #a29bfe; transform: translateY(-3px); }
        .error { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($success)): ?>
            <h2>Berhasil!</h2>
            <p><?php echo $success; ?></p>
        <?php else: ?>
            <h2 class="error">Ups!</h2>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <a href="index.html" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
