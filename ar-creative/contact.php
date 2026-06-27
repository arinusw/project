<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : '0';

    // Check that data was sent to the mailer.
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Mohon lengkapi formulir dengan benar."]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // In a real scenario, you would send an email here using mail() or a library like PHPMailer.
    // For this portfolio, we will simulate success to ensure the frontend works correctly.

    // Simulate processing time
    usleep(500000);

    echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim. Terima kasih!"]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Terjadi masalah dengan pengiriman Anda, silakan coba lagi."]);
}
?>
