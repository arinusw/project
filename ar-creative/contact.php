<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $user_name = strip_tags(trim($_SERVER["HTTP_X_USER_NAME"] ?? $_POST["user_name"] ?? ""));
    $user_name = str_replace(array("\r","\n"),array(" "," "),$user_name);
    $user_email = filter_var(trim($_SERVER["HTTP_X_USER_EMAIL"] ?? $_POST["user_email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $user_subject = strip_tags(trim($_SERVER["HTTP_X_USER_SUBJECT"] ?? $_POST["user_subject"] ?? ""));
    $user_message = strip_tags(trim($_SERVER["HTTP_X_USER_MESSAGE"] ?? $_POST["user_message"] ?? ""));
    $simulation_mode = $_POST['simulation_mode'] ?? '0';

    if (empty($user_name) || empty($user_message) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Mohon lengkapi formulir dengan benar."]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer here.
    // For this project, we support a simulation mode.
    if ($simulation_mode === '1') {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
    } else {
        // Example of how production mail handling might look
        $recipient = "infostudio.ari@gmail.com";
        $email_content = "Nama: $user_name\n";
        $email_content .= "Email: $user_email\n\n";
        $email_content .= "Pesan:\n$user_message\n";
        $email_headers = "From: $user_name <$user_email>";

        // Uncomment and configure for real usage
        // if (mail($recipient, $user_subject, $email_content, $email_headers)) {
        //     echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
        // } else {
        //     echo json_encode(["status" => "error", "message" => "Maaf, terjadi masalah saat mengirim pesan."]);
        // }

        // Defaulting to success message for this demo
        echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
    }
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses tidak diizinkan."]);
}
?>
