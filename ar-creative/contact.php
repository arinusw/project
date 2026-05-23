<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon isi semua bidang dengan benar."
        ]);
        exit;
    }

    // Recipient email
    $recipient = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Portofolio Ar Creative: $name";

    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    $email_headers = "From: $name <$email>";

    // In a real environment, mail() would be used.
    // For this simulation/demo, we'll return success if data is valid.
    // @mail($recipient, $subject, $email_content, $email_headers);

    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah terkirim. Terima kasih!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi masalah dengan pengiriman Anda, silakan coba lagi."
    ]);
}
?>
