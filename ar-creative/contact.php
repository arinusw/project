<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST['user_subject']));
    $message = trim($_POST['user_message']);
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Silakan lengkapi formulir dengan data yang valid."
        ]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer here.
    // For this portfolio template, we'll return a success message.

    echo json_encode([
        "status" => "success",
        "message" => "Terima kasih! Pesan Anda telah terkirim."
    ]);
} else {
    http_response_code(403);
    echo json_encode([
        "status" => "error",
        "message" => "Metode tidak diizinkan."
    ]);
}
?>
