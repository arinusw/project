<?php
/**
 * PHP Contact Form Handler for Ar Creative
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Tolong isi semua kolom dengan benar.";
        exit;
    }

    // Success response - normally mail() would be called here
    // For this demonstration, we'll provide a styled success message
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Terkirim | Ar Creative</title>
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
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        h1 { color: hsl(250, 69%, 61%); margin-bottom: 1rem; }
        p { color: hsl(250, 8%, 45%); margin-bottom: 2rem; }
        .button {
            display: inline-block;
            background-color: hsl(250, 69%, 61%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .button:hover { background-color: hsl(250, 57%, 53%); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Terima Kasih!</h1>
        <p>Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda kembali.</p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
<?php
} else {
    // Not a POST request, redirect to index
    header("Location: index.html");
    exit;
}
?>
