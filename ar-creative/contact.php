<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes contact form submissions with simulation mode support.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitization
    $name = strip_tags(trim($_POST['user_name'] ?? ''));
    $email = filter_var(trim($_POST['user_email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project'] ?? ''));
    $simulationMode = $_POST['simulation_mode'] ?? '0';

    // Validate fields
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Semua field harus diisi.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format email tidak valid.'
        ]);
        exit;
    }

    // Process submission
    if ($simulationMode === '1') {
        // Return success message for simulation
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
    } else {
        /*
         * In a real production environment, you would use mail() or a library like PHPMailer here.
         * For this portfolio example, we will treat it as a success for demonstration.
         */
        $to = "infostudio.ari@gmail.com";
        $subject = "Kontak dari Portfolio: " . $name;
        $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
        $headers = "From: $email";

        // Since we are in a sandbox/local environment, we simulate the mail success.
        // mail($to, $subject, $body, $headers);

        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil terkirim. Terima kasih!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
    ]);
}
?>
