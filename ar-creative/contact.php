<?php
/**
 * Contact Form Handler for Ar Creative
 */

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["user_subject"]));
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST["simulation_mode"]) && $_POST["simulation_mode"] == "1";

    // Validate data
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi semua kolom dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode) {
        // Simulation mode for testing in environment without mail support
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // Production mail handling (commented out for this demo/environment)
    /*
    $recipient = "infostudio.ari@gmail.com";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah berhasil dikirim!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Maaf, terjadi kesalahan saat mengirim pesan."
        ]);
    }
    */

    // Default response for production when mail is not yet configured
    echo json_encode([
        "status" => "success",
        "message" => "Terima kasih! Pesan Anda telah diterima (Mode Demo)."
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode tidak diizinkan."
    ]);
}
?>
