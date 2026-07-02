<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi formulir dengan benar.'
        ]);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // In a real scenario, we would use mail() or a library like PHPMailer
    // For this example, we'll simulate a successful submission
    /*
    $to = "info@arcreative.com";
    $subject = "Pesan Baru dari $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan terkirim!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan.']);
    }
    */

    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah kami terima.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses tidak diizinkan.'
    ]);
}
?>
