<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));

    // Basic validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Harap isi semua bidang dengan benar."
        ]);
        exit;
    }

    // In a real environment, you would use mail() or a library like PHPMailer
    // For this portfolio, we simulate a successful send.

    // Example of how mail would be sent:
    // $to = "infostudioari@example.com";
    // $subject = "Pesan Baru dari $name";
    // $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    // $headers = "From: $email";
    // @mail($to, $subject, $body, $headers);

    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah berhasil dikirim! ✅"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode permintaan tidak valid."
    ]);
}
?>
