<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi semua kolom yang diperlukan.'
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

    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan terkirim! (Mode Simulasi)'
        ]);
        exit;
    }

    // Production mail delivery (attempt)
    $to = 'infostudio ari'; // As per memory
    $subject = 'Pesan Baru dari Portofolio Ar Creative';
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    $headers = "From: $name <$email>";

    if (@mail($to, $subject, $email_content, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim.'
        ]);
    } else {
        // Fallback for local servers where mail() is not configured
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan diterima, namun pengiriman email gagal di server ini (Local Mode).'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
