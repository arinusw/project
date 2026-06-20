<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Basic validation
    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Mohon lengkapi form dengan benar."]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer here.
    // For this portfolio, we simulate success.

    if ($simulation_mode === "1") {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
    } else {
        // This is where actual mail sending logic would go
        // Example: mail($recipient, $subject, $email_content, $email_headers)

        echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim. Terima kasih!"]);
    }

} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Ada masalah dengan pengiriman Anda, silakan coba lagi."]);
}
?>
