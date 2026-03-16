<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"] ?? ''));

    // In a real scenario, you would send an email here
    // $to = "infostudio.ari@gmail.com";
    // $subject = "Pesan Baru dari $name";
    // $body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
    // mail($to, $subject, $body);

    // For this demonstration, we'll just show a success message
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9ff;
            color: #444;
            text-align: center;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        h1 { color: #6e57e0; margin-bottom: 1rem; }
        p { margin-bottom: 2rem; line-height: 1.6; }
        .button {
            display: inline-block;
            background-color: #6e57e0;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: 0.3s;
        }
        .button:hover { background-color: #5a43cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Terima Kasih!</h1>
        <p>Halo, <strong><?php echo htmlspecialchars($name); ?></strong>. Pesan Anda telah kami terima. Kami akan segera menghubungi Anda melalui email (<?php echo htmlspecialchars($email); ?>).</p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
<?php
} else {
    header("Location: index.html");
    exit();
}
?>
