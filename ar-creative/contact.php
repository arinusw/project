<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim(str_replace(array("\r","\n"),array(" "," "), $_POST["user_name"])));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Validate data
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Harap lengkapi formulir dengan data yang valid."
        ]);
        exit;
    }

    if ($simulation_mode === "1") {
        echo json_encode([
            "status" => "success",
            "message" => "Mode Simulasi: Pesan berhasil diterima!"
        ]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer
    // For this demonstration, we'll return a success message
    /*
    $recipient = "info@arcreative.com";
    $subject = "Pesan baru dari $name - Ar Creative Portfolio";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Maaf, terjadi masalah saat mengirim pesan."]);
    }
    */

    // Placeholder response for demonstration
    echo json_encode([
        "status" => "success",
        "message" => "Terima kasih! Pesan Anda telah kami terima."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode permintaan tidak valid."
    ]);
}
?>
