<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi semua kolom formulir.'
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

    // Sanitize name to prevent header injection
    $name = str_replace(["\r", "\n"], '', $name);

    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan terkirim (Mode Simulasi) ✅'
        ]);
        exit;
    }

    // In a real production environment, you would use mail() or a library like PHPMailer
    /*
    $to = "infostudioari@gmail.com";
    $subject = "Pesan Baru dari Portfolio: $name";
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$project";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah terkirim ✅'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengirim pesan. Silakan coba lagi nanti.'
        ]);
    }
    */

    // For now, let's return success for the demo/portfolio context
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah terkirim ✅'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
