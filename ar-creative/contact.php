<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic sanitization
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi form dengan benar."
        ]);
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Portfolio: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Check for simulation mode
    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan berhasil dikirim (Mode Simulasi)."
        ]);
        exit;
    }

    // Send email
    if (@mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah berhasil dikirim!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Ups! Terjadi kesalahan dan pesan Anda tidak dapat dikirim."
        ]);
    }

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
