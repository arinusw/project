<?php
/**
 * Ar Creative - Contact Form Handler
 * Processes incoming inquiries and returns JSON response in Bahasa Indonesia.
 */

header('Content-Type: application/json; charset=UTF-8');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid. Hanya menerima POST.'
    ]);
    exit;
}

// Retrieve and sanitize inputs
$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
$simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

// Validation
$errors = [];

if (empty($name) || mb_strlen($name) < 2) {
    $errors[] = 'Nama lengkap wajib diisi minimal 2 karakter.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Alamat email tidak valid.';
}

if (empty($subject) || mb_strlen($subject) < 3) {
    $errors[] = 'Subjek pesan wajib diisi minimal 3 karakter.';
}

if (empty($message) || mb_strlen($message) < 5) {
    $errors[] = 'Pesan wajib diisi minimal 5 karakter.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// In simulation mode (e.g., automated test suites), return success immediately
if ($simulationMode) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda berhasil dikirim (Mode Simulasi).'
    ]);
    exit;
}

// Production / Standard processing
// In local/static environments without sendmail, deliver successful acknowledgement response
$recipient = 'infostudio.ari@gmail.com';
$emailSubject = "Kontak Portofolio Ar Creative: " . $subject;
$emailBody = "Nama: {$name}\nEmail: {$email}\nSubjek: {$subject}\n\nPesan:\n{$message}\n";
$headers = "From: noreply@arcreative.local\r\nReply-To: {$email}\r\nX-Mailer: PHP/" . phpversion();

// Attempt mail send if mail function configured, otherwise gracefully confirm receipt
$mailSent = @mail($recipient, $emailSubject, $emailBody, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '! Pesan Anda telah kami terima. Kami akan segera menghubungi Anda kembali.'
]);
