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
            "message" => "Silakan lengkapi formulir dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode) {
        // Simulate success for development/testing
        echo json_encode([
            "status" => "success",
            "message" => "Pesan terkirim (Mode Simulasi). Terima kasih!"
        ]);
    } else {
        // Production mail configuration
        $recipient = "infostudio.ari@gmail.com";
        $subject = "Kontak Baru dari $name - Ar Creative";
        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$project\n";
        $email_headers = "From: $name <$email>";

        // Attempt to send email
        if (@mail($recipient, $subject, $email_content, $email_headers)) {
            echo json_encode([
                "status" => "success",
                "message" => "Pesan Anda telah berhasil dikirim. Terima kasih!"
            ]);
        } else {
            // Even if mail fails (e.g. no local mail server), we might return success if we want to bypass server errors in dev
            echo json_encode([
                "status" => "error",
                "message" => "Maaf, terjadi kesalahan saat mengirim pesan. Silakan hubungi kami via WhatsApp."
            ]);
        }
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Akses tidak diizinkan."
    ]);
}
?>
