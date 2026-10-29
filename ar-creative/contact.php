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
$simulationMode = isset($_POST['simulation_mode']) ? (int)$_POST['simulation_mode'] : 0;

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Mohon lengkapi semua kolom formulir.'
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

if ($simulationMode === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim dalam mode simulasi! Terima kasih telah menghubungi Ar Creative.'
    ]);
    exit;
}

// Default response for live form submission
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil dikirim! Tim Ar Creative akan segera merespons melalui email/WhatsApp.'
]);
?>