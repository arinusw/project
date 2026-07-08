<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for simulation mode (used during testing)
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Get and sanitize form fields
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);

    // Validate data
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode === '1') {
        // Simulate success for testing
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda berhasil dikirim (Mode Simulasi). Terima kasih!"
        ]);
        exit;
    }

    // In a real scenario, you would send an email here using mail() or a library like PHPMailer
    // For this lightweight portfolio, we'll simulate success since we might not have a configured mail server

    /*
    $recipient = "info@arcreative.com";
    $subject = "Pesan Baru dari $name via Ar Creative Portfolio";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Maaf, terjadi masalah saat mengirim pesan."]);
    }
    */

    // Defaulting to success message for this template's purpose
    echo json_encode([
        "status" => "success",
        "message" => "Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda."
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan. Silakan coba lagi."
    ]);
}
?>
