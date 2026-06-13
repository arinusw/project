<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["user_subject"]));
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Basic validation
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Harap lengkapi semua field dengan benar."]);
        exit;
    }

    if ($simulation_mode === "1") {
        // Return success for simulation mode
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // For this project, we prioritize simulation/fallback
    $recipient = "infostudio.ari@gmail.com";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // Since we don't have a mail server configured in this environment,
    // we use simulation as the primary successful path for now.
    // If you want to enable actual mail, uncomment below:
    /*
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengirim pesan."]);
    }
    */

    // Defaulting to a simulated success message if mail fails or isn't attempted
    echo json_encode(["status" => "success", "message" => "Pesan berhasil diterima (Simulasi)."]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan."]);
}
?>
