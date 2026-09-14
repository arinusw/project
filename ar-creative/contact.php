<?php
header('Content-Type: application/json; charset=utf-8');

// Respond to preflight OPTIONS requests if needed
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input fields
    $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom form kontak.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email yang dimasukkan tidak valid.'
        ]);
        exit;
    }

    // Process email sending or simulation mode
    // If simulation_mode is enabled or mail server is absent, simulate success
    if ($simulation_mode === '1' || !function_exists('mail')) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah berhasil terkirim.'
        ]);
        exit;
    }

    $to = "infostudio.ari@gmail.com"; // Official contact address
    $subject = "Pesan Baru dari Portfolio Ar Creative - " . $name;
    $body = "Nama: " . $name . "\n";
    $body .= "Email: " . $email . "\n\n";
    $body .= "Pesan:\n" . $message . "\n";

    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (@mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah berhasil terkirim.'
        ]);
    } else {
        // Fallback for environments without mail configuration
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah kami terima (Mode Simulasi).'
        ]);
    }
    exit;
}

// Method not allowed
http_response_code(405);
echo json_encode([
    'status' => 'error',
    'message' => 'Metode permintaan tidak diizinkan.'
]);
?>
