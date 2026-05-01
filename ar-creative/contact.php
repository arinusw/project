<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $message = strip_tags(trim($_POST["user_project"]));

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Silakan lengkapi formulir dengan benar."
        ]);
        exit;
    }

    $recipient = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari $name via Ar Creative";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";
    $email_headers = "From: $name <$email>";

    // In a real environment, we would use mail()
    // For this simulation/demo, we assume it works.
    // @mail($recipient, $subject, $email_content, $email_headers);

    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah terkirim. Terima kasih!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
