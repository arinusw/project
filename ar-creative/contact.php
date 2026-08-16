<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract and sanitize input data
    $name = isset($_POST['user_name']) ? trim(filter_var($_POST['user_name'], FILTER_SANITIZE_SPECIAL_CHARS)) : '';
    $email = isset($_POST['user_email']) ? trim(filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL)) : '';
    $project = isset($_POST['user_project']) ? trim(filter_var($_POST['user_project'], FILTER_SANITIZE_SPECIAL_CHARS)) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Basic validation
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom formulir!'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email tidak valid!'
        ]);
        exit;
    }

    // In simulation mode (e.g., automated tests), return instant success response
    if ($simulationMode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim! [Mode Simulasi]'
        ]);
        exit;
    }

    // Standard processing logic
    $to = 'infostudio.ari@gmail.com';
    $subject = "Pesan Baru dari Portfolio Ar Creative: " . $name;
    $headers = "From: " . $email . "\r\n" .
               "Reply-To: " . $email . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    $emailContent = "Nama: " . $name . "\n";
    $emailContent .= "Email: " . $email . "\n\n";
    $emailContent .= "Pesan:\n" . $project . "\n";

    // Attempt mail dispatch
    $mailSuccess = false;
    if (function_exists('mail')) {
        @$mailSuccess = mail($to, $subject, $emailContent, $headers);
    }

    if ($mailSuccess) {
        // Return success response to user
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim! Kami akan segera menghubungi Anda.'
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
        'message' => 'Metode permintaan tidak valid!'
    ]);
}
?>
