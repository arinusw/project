<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $project = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

    if (empty($name) || empty($project) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Silakan isi formulir dengan benar.'
        ]);
        exit;
    }

    // In a real scenario, you would send an email here using mail() or a library like PHPMailer
    // For this portfolio, we simulate success

    // Simulate processing time
    // usleep(500000);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
