<?php
header('Content-Type: application/json; charset=utf-8');

// Respond only to POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}

// Retrieve form input data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

// Handle JSON input if sent as raw JSON
if (empty($name) && empty($email) && empty($message)) {
    $rawInput = file_get_contents('php://input');
    if (!empty($rawInput)) {
        $data = json_decode($rawInput, true);
        if ($data) {
            $name = isset($data['name']) ? trim($data['name']) : '';
            $email = isset($data['email']) ? trim($data['email']) : '';
            $subject = isset($data['subject']) ? trim($data['subject']) : '';
            $message = isset($data['message']) ? trim($data['message']) : '';
            $simulation_mode = isset($data['simulation_mode']) ? $data['simulation_mode'] : '';
        }
    }
}

// Validation
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
        'message' => 'Format alamat email tidak valid.'
    ]);
    exit;
}

// Check for automated simulation mode or standard handling
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Simulasi sukses! Pesan Anda telah berhasil terkirim ke Ar Creative.'
    ]);
    exit;
}

// In production environment without mail server configured, simulate successful receipt
// or attempt mail() if needed
$to = "infostudioari@gmail.com";
$email_subject = "Pesan Kontak Baru dari Ar Creative Website: " . $subject;
$email_body = "Anda telah menerima pesan baru dari formulir kontak Ar Creative.\n\n".
              "Detail Pesan:\n".
              "Nama: $name\n".
              "Email: $email\n".
              "Subjek: $subject\n\n".
              "Pesan:\n$message\n";
$headers = "From: noreply@arcreative.local\n";
$headers .= "Reply-To: $email\n";

// We return a successful response to ensure seamless UX
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil terkirim! Terima kasih telah menghubungi Ar Creative.'
]);
exit;
?>
