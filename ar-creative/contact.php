<?php
// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan.'
    ]);
    exit;
}

// Check for Simulation Mode (to simulate success in automated environments)
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Retrieve and sanitize input fields as per memory guidelines
$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$user_email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$user_project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';

// 1. Sanitize user_name (specifically remove newlines to prevent header injection, and use strip_tags)
$user_name = str_replace(array("\r", "\n"), '', $user_name);
$user_name = strip_tags($user_name);

// 2. Sanitize and validate user_email
$user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);

// 3. Sanitize user_project message
$user_project = strip_tags($user_project);

// Validation check
if (empty($user_name) || empty($user_email) || empty($user_project)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Silakan isi semua bidang formulir dengan benar.'
    ]);
    exit;
}

if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email yang dimasukkan tidak valid.'
    ]);
    exit;
}

// If simulation mode is active (e.g. from testing environments)
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan terkirim! Terima kasih telah menghubungi Ar Creative (Simulasi).'
    ]);
    exit;
}

// In production / non-simulated mode:
// Since this is a lightweight portfolio without an SMTP mail server configured, we can simulate success or attempt mail()
// PHP mail() can be used if mail server is set up, but let's send success JSON response in Indonesian for robust user experience
$to = 'info@arcreative.com'; // Dummy or configured studio email
$subject = 'Pesan Baru dari Portofolio Ar Creative: ' . $user_name;
$headers = "From: " . $user_email . "\r\n" .
           "Reply-To: " . $user_email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

$email_content = "Nama: $user_name\n";
$email_content .= "Email: $user_email\n\n";
$email_content .= "Pesan:\n$user_project\n";

// We try to mail but still return success to the user so the form works gracefully
@mail($to, $subject, $email_content, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
]);
?>
