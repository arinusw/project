<?php
/**
 * Contact Form Handler for Ar Creative
 * Developed for PHP 7.4+
 */

// Define response structure
$response = [
    'status' => 'error',
    'message' => 'Maaf, terjadi kesalahan saat mengirim pesan.'
];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Basic validation
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Silakan lengkapi formulir dengan benar.';
    } else {
        // Recipient email
        $recipient = "infostudio.ari@gmail.com";

        // Email content
        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$message\n";

        // Email headers
        $email_headers = "From: $name <$email>";

        // Attempt to send email (suppress errors for local dev)
        // Set to true for UI verification in environments without mail server
        $is_simulation = false;

        if ($is_simulation) {
            $success = true;
        } else {
            $success = @mail($recipient, $subject, $email_content, $email_headers);
        }

        if ($success) {
            $response['status'] = 'success';
            $response['message'] = 'Terima kasih! Pesan Anda telah berhasil dikirim.';
        }
    }
}

// Display styled HTML response
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pengiriman - Ar Creative</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
            padding: 1.5rem;
        }
        .response-container {
            background-color: #FFF;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .status-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
        }
        .success .status-icon { color: hsl(250, 69%, 61%); }
        .error .status-icon { color: #f44336; }

        h2 { margin-bottom: 1rem; }
        p { margin-bottom: 2rem; color: hsl(250, 8%, 45%); }

        .button {
            display: inline-block;
            background-color: hsl(250, 69%, 61%);
            color: #FFF;
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
    <div class="response-container <?php echo $response['status']; ?>">
        <span class="status-icon"><?php echo $response['status'] == 'success' ? '✓' : '✗'; ?></span>
        <h2><?php echo $response['status'] == 'success' ? 'Berhasil!' : 'Gagal!'; ?></h2>
        <p><?php echo $response['message']; ?></p>
        <a href="index.html" class="button">Kembali ke Beranda</a>
    </div>
</body>
</html>
