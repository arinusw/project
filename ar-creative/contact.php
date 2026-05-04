<?php
/**
 * Ar Creative - Contact Form Handler
 * Simple PHP script to handle contact form submissions via AJAX.
 * For demonstration and proof-of-concept purposes.
 */

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

    // Basic validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "Mohon isi semua data dengan benar."
        ]);
        exit;
    }

    // In a real scenario, you would send an email here.
    // For this project, we simulate a successful submission.

    // Simulation: Always success for this demonstration
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah berhasil dikirim. Terima kasih!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode pengiriman tidak valid."
    ]);
}
?>
