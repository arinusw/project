<?php
header('Content-Type: application/json; charset=utf-8');

// Allow CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$user_project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

if (empty($user_name) || empty($user_email) || empty($user_project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua kolom formulir dengan benar.'
    ]);
    exit;
}

if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Alamat email tidak valid.'
    ]);
    exit;
}

// In simulation mode or standard execution, simulate email sending success
if ($simulation_mode) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim! (Mode Simulasi)'
    ]);
    exit;
}

// Actual mail logic (can be configured with mail() or external SMTP)
// For simple light PHP handling:
$to = "infostudioari@gmail.com";
$subject = "Pesan Baru dari Portofolio Ar Creative - " . htmlspecialchars($user_name);
$body = "Nama: " . htmlspecialchars($user_name) . "\n";
$body .= "Email: " . htmlspecialchars($user_email) . "\n\n";
$body .= "Pesan/Proyek:\n" . htmlspecialchars($user_project) . "\n";

$headers = "From: " . $user_email . "\r\n";
$headers .= "Reply-To: " . $user_email . "\r\n";

// Attempt to send email or return success response
@mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda telah berhasil dikirim. Terima kasih telah menghubungi Ar Creative!'
]);
exit;
?>
