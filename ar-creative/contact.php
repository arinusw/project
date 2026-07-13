<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if it's a simulation (from memories)
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Get form data
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);

    // Check that data was sent to the mailer.
    if ( empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon lengkapi formulir dengan benar."
        ]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan terkirim (Mode Simulasi)!"
        ]);
        exit;
    }

    // In a real scenario, we would use mail() or a library like PHPMailer
    // For this portfolio template, we will return success to show the UI works

    // $recipient = "your-email@example.com";
    // $subject = "Kontak Baru dari $name";
    // $email_content = "Nama: $name\n";
    // $email_content .= "Email: $email\n\n";
    // $email_content .= "Pesan:\n$project\n";
    // $email_headers = "From: $name <$email>";

    // if (mail($recipient, $subject, $email_content, $email_headers)) {
    //     echo json_encode(["status" => "success", "message" => "Pesan Anda telah terkirim!"]);
    // } else {
    //     echo json_encode(["status" => "error", "message" => "Oops! Terjadi kesalahan saat mengirim pesan."]);
    // }

    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah terkirim! Terima kasih telah menghubungi kami."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode permintaan tidak valid."
    ]);
}
?>
