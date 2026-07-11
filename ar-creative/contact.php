<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);

    // Check if data is valid
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Mohon lengkapi formulir dengan benar."]);
        exit;
    }

    // In a real scenario, you would send an email here
    // $recipient = "infostudioari@example.com";
    // $subject = "Pesan Baru dari $name";
    // $email_content = "Nama: $name\nEmail: $email\n\nPesan:\n$project\n";
    // $email_headers = "From: $name <$email>";
    // mail($recipient, $subject, $email_content, $email_headers);

    // Simulation of success for this project
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim! Terima kasih telah menghubungi Ar Creative."]);
} else {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi."]);
}
?>
