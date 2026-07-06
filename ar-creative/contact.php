<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's a simulation
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);

    // Basic validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Harap lengkapi semua bidang dengan benar."]);
        exit;
    }

    if ($simulation_mode === '1') {
        // Return success for simulation
        echo json_encode(["status" => "success", "message" => "Pesan terkirim (Mode Simulasi) ✅"]);
        exit;
    }

    // In a real scenario, you would send an email here
    // $recipient = "info@arcreative.com";
    // $subject = "Pesan Baru dari $name";
    // $email_content = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    // $email_headers = "From: $name <$email>";
    // mail($recipient, $subject, $email_content, $email_headers);

    echo json_encode(["status" => "success", "message" => "Pesan Anda telah berhasil dikirim! ✅"]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Terjadi masalah dengan pengiriman Anda, silakan coba lagi."]);
}
?>
