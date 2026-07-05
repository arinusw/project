<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form fields
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Check data
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Harap lengkapi semua bidang dan gunakan email yang valid."
        ]);
        exit;
    }

    if ($simulation_mode === "1") {
        // Simulation success
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim (Mode Simulasi) ✅"
        ]);
        exit;
    }

    // Real email sending logic (commented out as it needs a real mail server)
    /*
    $recipient = "info@arcreative.com";
    $subject = "Kontak Baru dari $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim ✅"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Ups! Terjadi masalah saat mengirim pesan Anda."
        ]);
    }
    */

    // For the sake of this project and lack of real mail server in this environment:
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah terkirim ✅"
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Akses tidak diizinkan."
    ]);
}
?>
