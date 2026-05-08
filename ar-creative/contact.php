<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic sanitization
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));

    // Simple validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Silakan lengkapi formulir dengan benar."
        ]);
        exit;
    }

    // In a real scenario, you would send an email here
    // $to = "infostudio.ari@gmail.com";
    // $subject = "Pesan Baru dari $name";
    // $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    // @mail($to, $subject, $body);

    // Simulation for local development
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah terkirim! Terima kasih telah menghubungi Ar Creative."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
