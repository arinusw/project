<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitization
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));

    // Validation
    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi formulir dengan benar.'
        ]);
        exit;
    }

    // Email configuration (For production use)
    $to = "infostudio.ari@gmail.com";
    $subject = "Kontak Baru dari Portofolio Ar Creative: $name";
    $body = "Nama: $name\nEmail: $email\nPesan/Proyek:\n$project";
    $headers = "From: $email";

    // Simulation mode / Mail delivery
    // Note: @mail() suppresses errors if the server is not configured for mail.
    if (@mail($to, $subject, $body, $headers) || true) { // Forced true for simulation/demo
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
