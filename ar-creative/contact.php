<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : '0';

    // Check if simulation mode is active
    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi semua field dengan benar."
        ]);
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";

    // Subject
    $subject = "Pesan baru dari $name (via Ar Creative)";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email (Note: mail() might not work in some local environments without configuration)
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Terima kasih! Pesan Anda telah terkirim."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Oops! Terjadi kesalahan saat mengirim pesan."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode tidak diizinkan."
    ]);
}
?>
