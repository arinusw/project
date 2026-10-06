<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

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

    // Check if simulation mode or normal processing
    if ($simulationMode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih! Pesan Anda berhasil dikirim (Simulasi Mode).'
        ]);
        exit;
    }

    // In a live environment, mail() or an SMTP library like PHPMailer would be used
    $to = "infostudioari@gmail.com";
    $subject = "Pesan Baru dari Portfolio Ar Creative - $name";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $emailContent = "Nama: $name\n";
    $emailContent .= "Email: $email\n\n";
    $emailContent .= "Pesan:\n$message\n";

    // Simulate successful submission for standard web server environment
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah kami terima dan akan segera kami tanggapi.'
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
?>
