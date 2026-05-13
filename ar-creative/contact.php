<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));
    $simulation_mode = isset($_POST["simulation_mode"]) && $_POST["simulation_mode"] == "1";

    // Validate input
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode) {
        // Simulation success response
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim (Mode Simulasi). Terima kasih!"
        ]);
        exit;
    }

    // Recipient email
    $recipient = "infostudio ari"; // Adjusted from prompt to be realistic for email format if needed, but keeping prompt value

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email
    if (@mail($recipient, "Kontak Baru dari Website Ar Creative", $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah berhasil terkirim. Kami akan segera menghubungi Anda!"
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
        "message" => "Akses ditolak."
    ]);
}
?>
