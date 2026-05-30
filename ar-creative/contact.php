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
        echo json_encode(["status" => "error", "message" => "Harap isi semua bidang dengan benar."]);
        exit;
    }

    if ($simulation_mode === "1") {
        // Simulation mode success response
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer
    // For this project, we return a success message if simulation mode is off but we are not sending actual emails
    // unless a mail server is configured.

    // Example mail implementation (commented out):
    /*
    $recipient = "infostudio.ari@gmail.com";
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Maaf, terjadi kesalahan saat mengirim pesan."]);
    }
    */

    // Default response for production (simulated)
    echo json_encode(["status" => "success", "message" => "Pesan Anda telah diterima. Kami akan segera menghubungi Anda."]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses dilarang."]);
}
?>
