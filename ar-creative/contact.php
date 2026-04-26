<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulate = isset($_POST['simulate']) ? $_POST['simulate'] : '0';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Semua kolom harus diisi.'
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

    if ($simulate === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim (Simulasi).'
        ]);
        exit;
    }

    // In production, you would use mail()
    // For now, we simulate success
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari Portfolio: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
    $headers = "From: $email";

    if (@mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah terkirim.'
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
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
}
?>
