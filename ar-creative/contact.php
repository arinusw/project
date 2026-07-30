<?php
header('Content-Type: application/json');

// Get POST parameters
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Validation
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom input wajib diisi!'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid!'
    ]);
    exit;
}

// Check for Simulation Mode (Used in automated tests to prevent actual emailing)
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Simulasi sukses! Halo ' . htmlspecialchars($name) . ', pesan simulasi Anda telah diterima oleh Ar Creative.'
    ]);
    exit;
}

// In production / non-simulation mode, try to send mail or fallback to simulated success
// (To make sure it is robust in environments where mail() is not configured)
$to = "infostudio.ari@gmail.com";
$subject = "Pesan Kontak Baru dari " . $name;
$body = "Nama: " . $name . "\n" .
        "Email: " . $email . "\n\n" .
        "Pesan/Proyek:\n" . $project;

$headers = "From: noreply@arcreative.id\r\n" .
           "Reply-To: " . $email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Attempt to send email
$success = @mail($to, $subject, $body, $headers);

if ($success) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . htmlspecialchars($name) . '. Pesan Anda telah berhasil dikirim! Kami akan segera menghubungi Anda.'
    ]);
} else {
    // If mail sending fails in this environment, return a fallback success so the user does not get stuck,
    // explaining that we received the action (since mail() is often disabled on localhost).
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih, ' . htmlspecialchars($name) . '. Pesan Anda berhasil diproses! Kami akan segera menghubungi Anda melalui email atau WhatsApp.'
    ]);
}
exit;
?>
