<?php
/**
 * Ar Creative Contact Form Handler
 * Simple PHP script to handle contact form submissions
 */

// Set response header
header('Content-Type: text/html; charset=utf-8');

// Initialize variables
$name = $email = $message = "";
$error = "";
$success = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate Name
    if (empty($_POST["name"])) {
        $error = "Nama wajib diisi.";
    } else {
        $name = strip_tags(trim($_POST["name"]));
    }

    // Sanitize and validate Email
    if (empty($_POST["email"])) {
        $error = "Email wajib diisi.";
    } else {
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format email tidak valid.";
        }
    }

    // Sanitize Message
    if (empty($_POST["message"])) {
        $error = "Pesan tidak boleh kosong.";
    } else {
        $message = strip_tags(trim($_POST["message"]));
    }

    // If no errors, process the message (Simulated)
    if (empty($error)) {
        // In a real scenario, you would use mail() or a library like PHPMailer here
        // mail("infostudio.ari@gmail.com", "Pesan Baru dari $name", $message, "From: $email");

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9fb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #2d3436;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .icon.success { color: #6c5ce7; }
        .icon.error { color: #ff7675; }
        h2 { margin-bottom: 15px; }
        p { color: #636e72; margin-bottom: 30px; }
        .btn {
            display: inline-block;
            background: #6c5ce7;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn:hover {
            background: #a29bfe;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($success): ?>
            <div class="icon success"><i class='bx bx-check-circle'></i></div>
            <h2>Terima Kasih!</h2>
            <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali melalui email atau WhatsApp.</p>
        <?php else: ?>
            <div class="icon error"><i class='bx bx-error-circle'></i></div>
            <h2>Ups! Terjadi Kesalahan</h2>
            <p><?php echo $error ? htmlspecialchars($error) : "Terjadi masalah saat memproses permintaan Anda. Silakan coba lagi nanti."; ?></p>
        <?php endif; ?>
        <a href="index.html" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
