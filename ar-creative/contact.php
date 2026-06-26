<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["message" => "Oops! Ada masalah dengan pengiriman Anda. Silakan lengkapi formulir dan coba lagi."]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode(["message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // For this portfolio, we simulate success
    echo json_encode(["message" => "Pesan berhasil terkirim!"]);
} else {
    http_response_code(403);
    echo json_encode(["message" => "Ada masalah dengan pengiriman Anda, silakan coba lagi."]);
}
?>
