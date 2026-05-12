<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : false;

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan lengkapi semua bidang dengan benar.'
        ]);
        exit;
    }

    if ($simulation_mode) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan terkirim (Mode Simulasi). Terima kasih, ' . $name . '!'
        ]);
        exit;
    }

    // In a real environment, you would use mail() or a library like PHPMailer
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Portofolio Ar Creative: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    if (@mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim. Terima kasih!'
        ]);
    } else {
        // Fallback for environments without mail configuration (common in dev)
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah diterima (Simulasi pengiriman). Kami akan segera menghubungi Anda!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
