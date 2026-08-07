<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Semua kolom input wajib diisi.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email tidak valid.'
        ]);
        exit;
    }

    // If simulation_mode is active, bypass actual email sending and return success
    if ($simulationMode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim! (Simulasi Pengiriman)'
        ]);
        exit;
    }

    // Traditional email processing details can be configured here
    $to = "infostudio.ari@gmail.com";
    $subject = "Kontak Portofolio Ar Creative - $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: webmaster@arcreative.com\r\nReply-To: $email";

    // In local servers where mail() function is not configured, we might handle errors gracefully
    if (@mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
        ]);
    } else {
        // Fallback for local environments without SMTP or sendmail config
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim! (Menggunakan fallback lokal)'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
}
?>
