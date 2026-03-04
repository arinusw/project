<?php
/**
 * Ar Creative Contact Handler
 * Handles form submission from the contact section
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    // Validate data
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "error";
        $response_message = "Harap isi semua kolom dengan benar.";
    } else {
        // In a real scenario, you would send an email here
        // mail("infostudio.ari@gmail.com", "Kontak Ar Creative: $subject", "Dari: $name\nEmail: $email\n\nPesan:\n$message");

        http_response_code(200);
        $status = "success";
        $response_message = "Pesan Anda telah berhasil dikirim. Terima kasih telah menghubungi Ar Creative!";
    }
} else {
    // Not a POST request
    header("Location: index.html");
    exit;
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
            background-color: hsl(250, 60%, 99%);
            color: hsl(250, 8%, 15%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 {
            color: <?php echo ($status == "success") ? "hsl(250, 69%, 61%)" : "#e74c3c"; ?>;
            margin-bottom: 1rem;
        }
        p {
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: hsl(250, 69%, 61%);
            color: #fff;
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: 0.3s;
        }
        .button:hover {
            background-color: hsl(250, 57%, 53%);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo ($status == "success") ? "Terima Kasih!" : "Ups!"; ?></h1>
        <p><?php echo $response_message; ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
