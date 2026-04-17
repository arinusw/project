<?php
/**
 * Ar Creative Contact Form Handler
 * Handles form submission and provides a styled response.
 */

// Simulation mode for development/CI environments
$simulation_mode = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["message"]));

    // Validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $status = "error";
        $response_message = "Harap isi semua bidang dengan benar.";
    } else {
        $recipient = "infostudio.ari@gmail.com";
        $subject = "Pesan Baru dari $name via Ar Creative Website";

        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$message\n";

        $email_headers = "From: $name <$email>";

        if ($simulation_mode) {
            $status = "success";
            $response_message = "Terima kasih! Pesan Anda telah terkirim (Mode Simulasi).";
        } else {
            // Attempt to send email
            if (@mail($recipient, $subject, $email_content, $email_headers)) {
                http_response_code(200);
                $status = "success";
                $response_message = "Terima kasih! Pesan Anda telah terkirim.";
            } else {
                http_response_code(500);
                $status = "error";
                $response_message = "Ups! Terjadi kesalahan dan kami tidak dapat mengirim pesan Anda.";
            }
        }
    }
} else {
    http_response_code(403);
    $status = "error";
    $response_message = "Ada masalah dengan pengiriman Anda, silakan coba lagi.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman - Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: hsl(250, 60%, 99%);
            color: hsl(250, 8%, 15%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .card {
            background: #fff;
            padding: 3rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
        }
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .success .icon { color: hsl(250, 69%, 61%); }
        .error .icon { color: #e74c3c; }
        h1 { margin-bottom: 1rem; font-size: 1.5rem; }
        p { margin-bottom: 2rem; color: hsl(250, 8%, 45%); }
        .button {
            display: inline-block;
            background-color: hsl(250, 69%, 61%);
            color: #fff;
            padding: 1rem 2rem;
            border-radius: .5rem;
            text-decoration: none;
            font-weight: 500;
            transition: .3s;
        }
        .button:hover { background-color: hsl(250, 57%, 53%); }
    </style>
</head>
<body>
    <div class="card <?php echo $status; ?>">
        <div class="icon">
            <?php echo ($status == "success") ? '<i class="ri-checkbox-circle-line"></i>' : '<i class="ri-error-warning-line"></i>'; ?>
        </div>
        <h1><?php echo ($status == "success") ? "Berhasil!" : "Gagal!"; ?></h1>
        <p><?php echo $response_message; ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
