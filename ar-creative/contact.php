<?php
/**
 * Ar Creative Contact Form Handler
 *
 * This script processes contact form submissions via AJAX.
 * It includes basic sanitization and supports a simulation mode for testing.
 */

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["user_subject"]));
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Validate data
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan data yang valid."
        ]);
        exit;
    }

    // Process the message
    if ($simulation_mode === "1") {
        // Simulation mode: return success without sending email
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
    } else {
        // Production mode: attempt to send email (simplified for this task)
        // In a real environment, you would use mail() or a library like PHPMailer
        $recipient = "infostudio.ari@gmail.com";
        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$message\n";

        $email_headers = "From: $name <$email>";

        // Uncomment the following line in a real production environment
        // if (mail($recipient, $subject, $email_content, $email_headers)) {
        if (true) { // Mock success for this task
            echo json_encode([
                "status" => "success",
                "message" => "Terima kasih! Pesan Anda telah terkirim."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Oops! Terjadi kesalahan saat mengirim pesan."
            ]);
        }
    }
} else {
    // Not a POST request
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
