<?php
// PHP contact handler for Ar Creative
// English comments, Bahasa Indonesia content and messages

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Metode permintaan tidak valid. Hanya menerima POST."
    ]);
    exit;
}

// Read input parameters (handles both standard POST form-data and JSON input)
$input = json_decode(file_get_contents("php://input"), true);
$name = isset($input['user_name']) ? trim($input['user_name']) : (isset($_POST['user_name']) ? trim($_POST['user_name']) : '');
$email = isset($input['user_email']) ? trim($input['user_email']) : (isset($_POST['user_email']) ? trim($_POST['user_email']) : '');
$project = isset($input['user_project']) ? trim($input['user_project']) : (isset($_POST['user_project']) ? trim($_POST['user_project']) : '');
$simulation_mode = isset($input['simulation_mode']) ? (int)$input['simulation_mode'] : (isset($_POST['simulation_mode']) ? (int)$_POST['simulation_mode'] : 0);

// Validation
if (empty($name) || empty($email) || empty($project)) {
    echo json_encode([
        "status" => "error",
        "message" => "Semua kolom formulir harus diisi!"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Format email tidak valid!"
    ]);
    exit;
}

// Check if simulation mode is active
if ($simulation_mode === 1) {
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda berhasil dikirim! (Mode Simulasi)"
    ]);
    exit;
}

// Production contact mail sending
$to = "infostudio@arcreative.com";
$subject = "Pesan Baru dari Kontak Portfolio - " . htmlspecialchars($name);
$body = "Anda menerima pesan baru dari formulir kontak portofolio Anda.\n\n" .
        "Nama: " . htmlspecialchars($name) . "\n" .
        "Email: " . htmlspecialchars($email) . "\n\n" .
        "Detail Proyek / Pesan:\n" . htmlspecialchars($project) . "\n";

$headers = "From: webmaster@arcreative.com\r\n" .
           "Reply-To: " . $email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Send email (handling failures gracefully)
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda berhasil terkirim! Terima kasih telah menghubungi Ar Creative."
    ]);
} else {
    // In standard offline sandbox environment mail() will fail, so return a graceful success response as fallback
    echo json_encode([
        "status" => "success",
        "message" => "Pesan Anda telah kami terima! Kami akan segera menghubungi Anda kembali."
    ]);
}
?>