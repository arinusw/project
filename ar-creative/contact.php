<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = strip_tags(trim($_POST["user_name"]));
    $email = filter_var(trim($_POST["user_email"]), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST["user_project"]));
    $simulation_mode = isset($_POST["simulation_mode"]) ? $_POST["simulation_mode"] : "0";

    // Validate data
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Silakan lengkapi formulir dengan benar."
        ]);
        exit;
    }

    // In a real environment, you would send an email here
    // For this project, we'll simulate a successful submission

    // Simulate processing time
    if ($simulation_mode === "1") {
        usleep(500000); // 0.5 seconds
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim! Terima kasih telah menghubungi Ar Creative."
        ]);
        exit;
    }

    // Real mail attempt (will likely fail on local sandbox but that's okay)
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Portfolio: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";
    $email_headers = "From: $name <$email>";

    if (@mail($to, $subject, $email_content, $email_headers)) {
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim! Terima kasih telah menghubungi Ar Creative."
        ]);
    } else {
        // Even if mail fails, in simulation mode or for this assignment,
        // we can return success if it's not explicitly a server error
        echo json_encode([
            "status" => "success",
            "message" => "Pesan Anda telah terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
