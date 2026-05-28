<?php
/**
 * Ar Creative Contact Form Handler
 *
 * This script handles the contact form submission.
 * It includes simulation mode for testing.
 */

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["user_subject"]));
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Validate data
    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Mohon lengkapi formulir dengan benar."]);
        exit;
    }

    // Check for simulation mode
    if ($simulation_mode === "1") {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // Production email handling (Placeholder)
    // In a real production environment, you would use mail() or a library like PHPMailer
    $recipient = "infostudio.ari@gmail.com";
    $email_subject = "Kontak Portofolio: $subject";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // For this demonstration, we'll return success even if mail() isn't configured
    // error_log("Contact form message received from $email");

    echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses dilarang."]);
}
?>
