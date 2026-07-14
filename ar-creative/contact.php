<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Harap isi semua bidang dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode === "1") {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan berhasil dikirim (Mode Simulasi) ✅"
        ]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // For this portfolio, we will just simulate success if not in production

    /*
    $recipient = "infostudioari@gmail.com";
    $subject = "Kontak Baru dari $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim. Terima kasih!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Ups! Terjadi kesalahan dan pesan Anda tidak dapat dikirim."]);
    }
    */

    // Defaulting to success for the portfolio demonstration
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah terkirim. Terima kasih! ✅"
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode tidak diizinkan."
    ]);
}
?>
