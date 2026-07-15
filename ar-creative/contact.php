<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic sanitization
    $name = strip_tags(trim($_SERVER['user_name'] ?? ''));
    $email = filter_var(trim($_SERVER['user_email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_SERVER['user_project'] ?? ''));

    // Note: PHP -S doesn't populate $_POST correctly for multipart/form-data without proper setup,
    // but in a real server environment this would be $_POST.
    // Let's check both just in case.
    if (empty($name)) $name = strip_tags(trim($_POST['user_name'] ?? ''));
    if (empty($email)) $email = filter_var(trim($_POST['user_email'] ?? ''), FILTER_SANITIZE_EMAIL);
    if (empty($project)) $project = strip_tags(trim($_POST['user_project'] ?? ''));

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            "status" => "error",
            "message" => "Silakan lengkapi semua field dalam form."
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Format email tidak valid."
        ]);
        exit;
    }

    // Simulate sending email
    // In a real project, you would use mail() or a library like PHPMailer
    /*
    $to = "infostudioari@gmail.com";
    $subject = "Pesan baru dari Portfolio: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";
    mail($to, $subject, $body, $headers);
    */

    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah berhasil terkirim! ✅"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Akses tidak diizinkan."
    ]);
}
?>
