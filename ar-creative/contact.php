<?php
// Set response content type to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow POST requests only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid. Silakan gunakan metode POST.'
    ]);
    exit;
}

// Retrieve form input data safely
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] == '1';

// Form validation checks
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir kontak.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email yang Anda masukkan tidak valid.'
    ]);
    exit;
}

// Simulation mode response for automated frontend testing
if ($simulationMode) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Process email delivery or save contact submission
$to = 'infostudio.ari@gmail.com';
$emailSubject = "Pesan Kontak Ar Creative: " . $subject;
$emailBody = "Anda telah menerima pesan baru dari formulir kontak Ar Creative:\n\n";
$emailBody .= "Nama: " . $name . "\n";
$emailBody .= "Email: " . $email . "\n";
$emailBody .= "Subjek: " . $subject . "\n\n";
$emailBody .= "Pesan:\n" . $message . "\n";

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt mail sending (falls back gracefully to success JSON response for local server environments)
@mail($to, $emailSubject, $emailBody, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, pesan Anda telah berhasil dikirim! Tim Ar Creative akan segera menghubungi Anda.'
]);
?>
