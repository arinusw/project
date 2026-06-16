<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["user_message"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Validate data
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Harap lengkapi formulir dengan benar."]);
        exit;
    }

    if ($simulation_mode === "1") {
        // Simulation mode: just return success
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari $name";

    // Email content
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    // Send email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Maaf, terjadi kesalahan saat mengirim pesan."]);
    }
} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses dilarang."]);
}
?>
