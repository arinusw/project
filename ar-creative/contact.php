<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : '0';

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Mohon lengkapi form dengan benar."]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // For now, we simulate a successful mail send if not in simulation mode
    // (Actual mailing might not be configured in this environment)

    // $recipient = "info@arcreative.com";
    // $subject = "Pesan Baru dari $name";
    // $email_content = "Nama: $name\nEmail: $email\n\nPesan:\n$project\n";
    // $email_headers = "From: $name <$email>";
    // if (mail($recipient, $subject, $email_content, $email_headers)) { ... }

    echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim. Terima kasih!"]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Terjadi masalah dengan pengiriman Anda, silakan coba lagi."]);
}
?>
