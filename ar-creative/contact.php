<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulation = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom formulir yang wajib diisi.'
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

    // Process submission or simulation
    if ($simulation === '1' || $simulation === 1) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // Send email logic (if configured)
    $to = "infostudio.ari@gmail.com";
    $email_subject = !empty($subject) ? "Kontak Ar Creative: " . $subject : "Pesan Baru dari Portfolio Ar Creative";
    $headers = "From: " . $name . " <" . $email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Nama: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Subjek: " . $subject . "\n\n";
    $body .= "Pesan:\n" . $message . "\n";

    // Attempt mail sending
    @mail($to, $email_subject, $body, $headers);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi Ar Creative.'
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
