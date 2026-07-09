<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = str_replace(["\r", "\n"], '', strip_tags(trim($_POST['user_name'])));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua bidang dengan benar.'
        ]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim (Simulasi).'
        ]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // For this project, we prioritize the frontend and provide this simulation/logic
    $recipient = "info@arcreative.com";
    $subject = "Pesan Baru dari $name via Ar Creative";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    $email_headers = "From: $name <$email>";

    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih! Pesan Anda telah terkirim.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Maaf, terjadi kesalahan saat mengirim pesan.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
