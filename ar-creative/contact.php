<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));
    $simulation_mode = isset($_POST["simulation_mode"]) && $_POST["simulation_mode"] == "1";

    // Basic validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Harap isi semua formulir dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode) {
        // Simulate success for development/testing
        echo json_encode([
            "status" => "success",
            "message" => "Pesan terkirim (Mode Simulasi)! ✅"
        ]);
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Portfolio Ar Creative: $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email (suppress errors with @ for local environments)
    if (@mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah berhasil dikirim! ✅"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi nanti."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
