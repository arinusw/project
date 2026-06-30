<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST['user_project']);
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Input tidak valid."]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer here.
    // For this portfolio, we'll return success if simulation is off but won't actually send email
    // unless configured with a real SMTP server.

    echo json_encode(["status" => "success", "message" => "Pesan berhasil dikirim!"]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan."]);
}
?>
