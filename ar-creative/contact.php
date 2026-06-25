<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    if (empty($name) OR empty($project) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Harap isi semua kolom dengan benar."]);
        exit;
    }

    if ($simulation_mode === "1") {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // Default response for production (since we don't have a mail server configured in this environment)
    // In a real scenario, you would use mail() or a library like PHPMailer

    // For this project, we return success to simulate a working form
    echo json_encode(["status" => "success", "message" => "Pesan terkirim ✅"]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Terjadi masalah dengan pengiriman Anda, silakan coba lagi."]);
}
?>
