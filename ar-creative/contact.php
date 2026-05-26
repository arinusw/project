<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? str_replace(["\r", "\n"], '', strip_tags(trim($_POST['user_name']))) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi semua kolom yang diperlukan.'
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

    if ($simulationMode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Mode Simulasi: Pesan berhasil diterima!'
        ]);
        exit;
    }

    // Production email sending logic
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Portofolio Ar Creative: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$project\n";

    $headers = "From: $name <$email>";

    if (mail($to, $subject, $email_content, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim!'
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
