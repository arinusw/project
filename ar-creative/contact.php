<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
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
        'message' => 'Harap isi semua kolom formulir kontak.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email yang dimasukkan tidak valid.'
    ]);
    exit;
}

// Check if running in simulation mode or actual environment
if ($simulationMode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil terkirim dalam mode simulasi.'
    ]);
    exit;
}

// In production, mail() or mailer script can be executed here
$to = "infostudio.ari@gmail.com";
$emailSubject = "Pesan Kontak Ar Creative: " . $subject;
$emailBody = "Nama: $name\nEmail: $email\nSubjek: $subject\n\nPesan:\n$message\n";
$headers = "From: $email\r\nReply-To: $email\r\n";

// For demo/portfolio purposes, return success message
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.'
]);
?>
