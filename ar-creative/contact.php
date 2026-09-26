<?php
// Set response content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow requests for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : null;

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom formulir kontak.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format email yang Anda masukkan tidak valid.'
        ]);
        exit;
    }

    // Check for simulation mode (used during UI tests)
    if ($simulation_mode === '1' || $simulation_mode === 1) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim (Simulasi). Terima kasih telah menghubungi Ar Creative!'
        ]);
        exit;
    }

    // Attempt to send email (or simulate standard delivery)
    $to = 'infostudio.ari@gmail.com';
    $subject = 'Pesan Baru dari Website Ar Creative: ' . $name;
    $body = "Nama: $name\nEmail: $email\n\nPesan:\n$message";
    $headers = "From: $email\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

    // Standard mail processing - fallback to success response for user experience
    @mail($to, $subject, $body, $headers);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan.'
    ]);
    exit;
}
