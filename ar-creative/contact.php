<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim(str_replace(array("\r","\n"),array(" "," "), $_POST["user_name"])));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["user_subject"]));
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Check simulation mode
    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // Basic validation
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Harap lengkapi semua bidang dengan benar."
        ]);
        exit;
    }

    // Prepare email (Production)
    $recipient = "infostudio.ari@gmail.com";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Send email
    if (mail($recipient, "Kontak Ar Creative: $subject", $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Terima kasih! Pesan Anda telah terkirim."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Maaf, terjadi kesalahan saat mengirim pesan."
        ]);
    }
} else {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Akses dilarang."
    ]);
}
?>
