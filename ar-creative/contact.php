<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $project = strip_tags(trim($_POST['user_project']));

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan lengkapi semua data form.'
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

    // Simulation mode - assume success
    // In a real scenario, you would use mail() or a library like PHPMailer
    // @mail("infostudio.ari@gmail.com", "Pesan Baru dari Ar Creative Portfolio", "Nama: $name\nEmail: $email\nPesan: $project");

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah terkirim ✅. Terima kasih!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
