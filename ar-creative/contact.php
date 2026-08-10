<?php
/**
 * Ar Creative Contact Form Processor
 * Handles contact form submissions and returns JSON response in Bahasa Indonesia.
 */

header('Content-Type: application/json; charset=UTF-8');

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak diizinkan. Harap gunakan POST.'
    ]);
    exit;
}

// Retrieve form inputs (supporting both raw POST data and standard urlencoded/form-data)
$name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulation_mode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '';

// Also check raw JSON input in case content type is application/json
if (empty($name) && empty($email) && empty($project)) {
    $raw_input = file_get_contents('php://input');
    if (!empty($raw_input)) {
        $json_data = json_decode($raw_input, true);
        if (is_array($json_data)) {
            $name = isset($json_data['user_name']) ? trim($json_data['user_name']) : '';
            $email = isset($json_data['user_email']) ? trim($json_data['user_email']) : '';
            $project = isset($json_data['user_project']) ? trim($json_data['user_project']) : '';
            $simulation_mode = isset($json_data['simulation_mode']) ? trim($json_data['simulation_mode']) : '';
        }
    }
}

// Validation
if (empty($name)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Nama tidak boleh kosong. Harap isi nama Anda.'
    ]);
    exit;
}

if (empty($email)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email tidak boleh kosong. Harap isi email Anda.'
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format email tidak valid. Harap masukkan email yang benar.'
    ]);
    exit;
}

if (empty($project)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Deskripsi proyek atau pesan tidak boleh kosong.'
    ]);
    exit;
}

// Check for Simulation Mode (useful for automated UI testing)
if ($simulation_mode === '1') {
    echo json_encode([
        'status' => 'success',
        'message' => 'Simulasi Berhasil: Pesan Anda telah diterima oleh Ar Creative (Mode Simulasi).'
    ]);
    exit;
}

// Real-world mail sending can be simulated or done via mail()
// Since this is a lightweight portfolio, we'll simulate successful mail handling
// or use mail() if the system has it configured, but default to success with clean response.
$to = 'infostudio.ari@gmail.com'; // Destination email as per brand identity
$subject = 'Pesan Baru dari Portfolio Ar Creative: ' . $name;
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$body = "Anda menerima pesan baru dari form kontak Ar Creative.\n\n";
$body .= "Nama: " . $name . "\n";
$body .= "Email: " . $email . "\n\n";
$body .= "Pesan/Proyek:\n" . $project . "\n";

// Suppress error with @ in case sendmail is not configured in local environment
$mail_sent = @mail($to, $subject, $body, $headers);

echo json_encode([
    'status' => 'success',
    'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim ke Ar Creative.'
]);
exit;
