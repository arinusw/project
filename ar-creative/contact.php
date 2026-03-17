<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "error";
        $title = "Kesalahan!";
        $msg = "Mohon isi formulir dengan benar dan coba lagi.";
    } else {
        // In a real scenario, you would send an email here using mail()
        // For this demo, we'll just simulate a success response
        http_response_code(200);
        $status = "success";
        $title = "Pesan Terkirim!";
        $msg = "Terima kasih, $name. Pesan Anda telah kami terima dan akan segera kami proses.";
    }
} else {
    http_response_code(403);
    $status = "error";
    $title = "Akses Ditolak";
    $msg = "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman | Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }
        h1 { color: <?php echo $status == "success" ? "#6c5ce7" : "#ff7675"; ?>; }
        p { color: #555; line-height: 1.6; }
        .button {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background-color: #6c5ce7;
            color: #fff;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: background 0.3s;
        }
        .button:hover { background-color: #5a4bcf; }
    </style>
</head>
<body>
    <div class="card">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p><?php echo htmlspecialchars($msg); ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
