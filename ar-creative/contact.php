<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua bidang formulir.'
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

    if ($simulation_mode === '1') {
        // Success simulation for testing
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan terkirim (Mode Simulasi) ✅'
        ]);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // For this portfolio, we return a success message
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah terkirim. Terima kasih! ✅'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
