<?php
/**
 * Contact form handler for Ar Creative.
 * Supports a simulation mode for development/testing.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST['user_message']);
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["message" => "Mohon lengkapi formulir dengan benar."]);
        exit;
    }

    if ($simulationMode === '1') {
        // Simulation mode: just return a success message
        echo json_encode(["message" => "Mode Simulasi: Pesan berhasil diterima!"]);
    } else {
        // Production mode: attempt to send email
        $recipient = "infostudio.ari@gmail.com";
        $subject = "Pesan baru dari $name (Ar Creative Portfolio)";
        $email_content = "Nama: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Pesan:\n$message\n";

        $email_headers = "From: $name <$email>";

        if (mail($recipient, $subject, $email_content, $email_headers)) {
            http_response_code(200);
            echo json_encode(["message" => "Terima kasih! Pesan Anda telah terkirim."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Oops! Terjadi kesalahan saat mengirim pesan."]);
        }
    }
} else {
    http_response_code(403);
    echo json_encode(["message" => "Akses dilarang."]);
}
?>
