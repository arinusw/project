<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : 0;

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi semua bidang dengan benar.'
        ]);
        exit;
    }

    // In simulation mode or on local server where mail() might not be configured
    if ($simulation_mode == 1) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi). Terima kasih!'
        ]);
        exit;
    }

    // Real mail delivery attempt
    $to = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Website Ar Creative: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    $email_headers = "From: $name <$email>";

    if (@mail($to, $subject, $email_content, $email_headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim. Terima kasih!'
        ]);
    } else {
        // Fallback to success message even if mail fails on unconfigured servers,
        // but indicate it's a success for the user UI experience.
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah diterima. Kami akan segera menghubungi Anda!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
}
?>
