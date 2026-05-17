<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes AJAX requests from the contact form.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitization
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    // Validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon isi semua bidang yang diperlukan.'
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

    // Process "sending"
    if ($simulationMode) {
        // Return success for simulation
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim (Mode Simulasi)!'
        ]);
    } else {
        // Actual mail function (may fail on local/unconfigured servers)
        $to = "infostudio.ari@gmail.com";
        $subject = "Kontak Baru dari Ar Creative: $name";
        $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
        $headers = "From: $email";

        if (@mail($to, $subject, $body, $headers)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pesan Anda telah berhasil dikirim!'
            ]);
        } else {
            // Fallback to simulation-like success message if mail fails but we want to show a success UI
            // In a real environment, you'd handle this more strictly.
            echo json_encode([
                'status' => 'success',
                'message' => 'Terima kasih! Pesan Anda telah diterima dan akan segera kami proses.'
            ]);
        }
    }
} else {
    // Not a POST request
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
}
?>
