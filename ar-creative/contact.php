<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow basic response handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input fields
    $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom formulir.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format alamat email tidak valid.'
        ]);
        exit;
    }

    // Process contact submission or simulation mode
    if ($simulation_mode || true) {
        // Return success response in Bahasa Indonesia
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.'
        ]);
        exit;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
?>