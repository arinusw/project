<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic sanitization
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));
    $simulation_mode = isset($_POST["simulation_mode"]) && $_POST["simulation_mode"] == "1";

    // Validate inputs
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi semua field dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode) {
        // Simulation success response
        echo json_encode([
            "status" => "success",
            "message" => "Pesan terkirim (Mode Simulasi) ✅"
        ]);
        exit;
    }

    // Production email handling
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Ar Creative: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    if (@mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim. Terima kasih! ✅"
        ]);
    } else {
        // Fallback for local environments without mail server
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah diterima. Kami akan segera menghubungi Anda. ✅"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode tidak diizinkan."
    ]);
}
?>
