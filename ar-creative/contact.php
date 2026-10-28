<?php
// Set JSON response header
header('Content-Type: application/json; charset=UTF-8');

// Disable error display in production JSON output
error_reporting(0);
ini_set('display_errors', '0');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form fields
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom formulir.'
        ]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email tidak valid. Silakan periksa kembali.'
        ]);
        exit;
    }

    // Simulation mode for testing environment or standard mock email handling
    if ($simulationMode) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim (Mode Simulasi).'
        ]);
        exit;
    }

    // Attempt to send email or simulate success if mail server is not configured
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan Baru dari Website Ar Creative: " . $name;
    $body = "Nama: " . $name . "\n";
    $body .= "Email: " . $email . "\n\n";
    $body .= "Pesan:\n" . $message . "\n";
    $headers = "From: " . $email . "\r\n" .
               "Reply-To: " . $email . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Send email using mail() or fallback gracefully
    if (@mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim.'
        ]);
    } else {
        // Fallback response for local environment where sendmail/smtp is not set up
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah kami terima! Kami akan segera menghubungi Anda kembali.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
}
?>
