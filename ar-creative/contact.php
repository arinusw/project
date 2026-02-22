<?php
/*
    Ar Creative - Contact Handler (PHP)
    Handles form submissions securely and provides a styled response.
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Simple validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = "error";
        $title = "Kesalahan!";
        $desc = "Mohon isi formulir dengan benar.";
    } else {
        // In a real scenario, you would send an email here using mail() or a library
        // mail("infostudio.ari@gmail.com", "Pesan Baru dari $name", $message, "From: $email");

        $status = "success";
        $title = "Terima Kasih!";
        $desc = "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.";
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
    <title>Status Pengiriman | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fafaff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .message-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            max-width: 400px;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .success .icon { color: #2ecc71; }
        .error .icon { color: #e74c3c; }
        h1 { margin-bottom: 10px; font-size: 1.5rem; }
        p { color: #636e72; margin-bottom: 30px; }
        .btn {
            background-color: #6c5ce7;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 500;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="message-box <?php echo $status; ?>">
        <div class="icon">
            <?php echo ($status == 'success') ? "<i class='bx bx-check-circle'></i>" : "<i class='bx bx-error-circle'></i>"; ?>
        </div>
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p><?php echo htmlspecialchars($desc); ?></p>
        <a href="index.html" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
