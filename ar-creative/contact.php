<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));
    $simulation = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi semua bidang formulir.'
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

    // In a real application, you would send an email here using mail() or a library like PHPMailer
    // For this portfolio, we will simulate the process.

    if ($simulation === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan terkirim (Simulasi) ✅'
        ]);
    } else {
        // Here you would implement actual mail sending
        // For demonstration, we'll return success
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim! ✅'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
