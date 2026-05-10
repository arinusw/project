<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic sanitization
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : false;

    // Check that data is valid
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode) {
        // Simulation mode success response
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah diterima (Mode Simulasi)."
        ]);
        exit;
    }

    // Email configuration (example)
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari $name di Ar Creative Website";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    // Send the email
    if (@mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Terima kasih! Pesan Anda telah terkirim."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Ups! Terjadi kesalahan saat mengirim pesan."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak diizinkan."
    ]);
}
?>
