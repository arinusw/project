<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = trim($_POST["user_project"]);

    // Check if simulation mode is active (for testing purposes)
    $simulation = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : 0;

    if ($simulation == 1) {
        echo json_encode(["status" => "success", "message" => "Mode Simulasi: Pesan berhasil diterima!"]);
        exit;
    }

    // Check that data is valid
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Mohon isi form dengan benar."]);
        exit;
    }

    // In a real scenario, you would send an email here using mail() or a library like PHPMailer
    // For this simple example, we'll just return a success message

    // $recipient = "info@arcreative.com";
    // $subject = "Pesan Baru dari $name";
    // $email_content = "Nama: $name\n";
    // $email_content .= "Email: $email\n\n";
    // $email_content .= "Pesan:\n$project\n";
    // $email_headers = "From: $name <$email>";

    // if (mail($recipient, $subject, $email_content, $email_headers)) {
    //     echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);
    // } else {
    //     echo json_encode(["status" => "error", "message" => "Maaf, terjadi kesalahan saat mengirim pesan."]);
    // }

    // Returning success as default for this portfolio project template
    echo json_encode(["status" => "success", "message" => "Terima kasih! Pesan Anda telah terkirim."]);

} else {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses ditolak."]);
}
?>
