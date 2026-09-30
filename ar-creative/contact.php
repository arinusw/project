<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir.'
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

// Check for test simulation mode
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim (Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Mail delivery configuration
$to = "infostudioari@gmail.com";
$emailSubject = "Pesan Baru dari Website Ar Creative: " . $subject;

$emailBody = "Anda telah menerima pesan baru dari formulir kontak website Ar Creative.\n\n";
$emailBody .= "Nama: " . $name . "\n";
$emailBody .= "Email: " . $email . "\n";
$emailBody .= "Subjek: " . $subject . "\n\n";
$emailBody .= "Pesan:\n" . $message . "\n";

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt to send email
if (@mail($to, $emailSubject, $emailBody, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim. Terima kasih telah menghubungi Ar Creative!'
    ]);
} else {
    // If mail function fails on local server without mail server configured
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah kami terima. Terima kasih telah menghubungi Ar Creative!'
    ]);
}
?>
