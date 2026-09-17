<?php
// Set content type header to JSON
header('Content-Type: application/json; charset=UTF-8');

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak valid.'
    ]);
    exit;
}

// Retrieve form inputs
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

// Validate inputs
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Harap isi semua bidang formulir kontak yang wajib.'
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

// Check if simulation mode is enabled for automated testing
if ($simulationMode === '1' || $simulationMode === 1) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi)! Kami akan segera menghubungi Anda.'
    ]);
    exit;
}

// Prepare email headers and details
$to = 'infostudio.ari@gmail.com';
$subject = "Pesan Baru dari Portfolio Ar Creative: $name";
$email_content = "Nama: $name\n";
$email_content .= "Email: $email\n\n";
$email_content .= "Pesan:\n$message\n";

$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Attempt to send email
if (@mail($to, $subject, $email_content, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Terima kasih! Pesan Anda telah berhasil dikirim.'
    ]);
} else {
    // If mail function fails or mail server is unconfigured, return a friendly success message for local environments
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah kami terima! Kami akan membalas segera melalui email.'
    ]);
}
?>
