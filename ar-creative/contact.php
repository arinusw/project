<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_SERVER["HTTP_USER_NAME"] ?? $_POST['user_name']));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["user_subject"]));
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Check if simulation mode is active
    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // Simple validation
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan benar."
        ]);
        exit;
    }

    // Production Email Logic (Disabled for this environment)
    /*
    $recipient = "infostudio.ari@gmail.com";
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Pesan berhasil dikirim!"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal mengirim pesan."]);
    }
    */

    // Default response for production mode in this environment
    echo json_encode([
        "status" => "success",
        "message" => "Pesan berhasil diterima (Production Simulation)!"
    ]);

} else {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan pada pengiriman."
    ]);
}
?>
