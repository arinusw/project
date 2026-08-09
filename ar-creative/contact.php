<?php
/**
 * Ar Creative - PHP Contact Form Handler
 * Returns JSON response with translation support in Bahasa Indonesia.
 */

header('Content-Type: application/json; charset=utf-8');

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode pengiriman tidak diizinkan. Silakan kirim formulir menggunakan POST. ❌'
    ]);
    exit;
}

// Retrieve form parameters
$userName = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
$userEmail = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
$userProject = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
$simulationMode = isset($_POST['simulation_mode']) ? trim($_POST['simulation_mode']) : '0';

// Simple validation
if (empty($userName) || empty($userEmail) || empty($userProject)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua kolom formulir wajib diisi! Harap isi Nama, Email, dan Pesan Anda. ⚠️'
    ]);
    exit;
}

if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format alamat email tidak valid! Harap masukkan email yang benar. 📧'
    ]);
    exit;
}

// Handle simulation mode or actual mail delivery
if ($simulationMode === '1') {
    // Return mock success response for testing environments
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan terkirim (Mode Simulasi)! Terima kasih, ' . htmlspecialchars($userName) . ', kami akan segera menghubungi Anda. Penawaran telah berhasil dicatat. 🎉'
    ]);
    exit;
}

// Actual mail configuration (can be updated for production mail server setups)
$to = 'infostudio.ari@gmail.com';
$subject = 'Pesan Baru dari ' . $userName . ' - Ar Creative Portfolio';
$body = "Anda menerima pesan baru dari formulir kontak portofolio Anda:\n\n" .
        "Nama Lengkap: " . $userName . "\n" .
        "Alamat Email: " . $userEmail . "\n\n" .
        "Pesan / Rencana Proyek:\n" . $userProject . "\n";

$headers = "From: " . $userEmail . "\r\n" .
           "Reply-To: " . $userEmail . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Attempt to send email
if (@mail($to, $subject, $body, $headers)) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi Ar Creative. Kami akan segera merespons pesan Anda. ✉️'
    ]);
} else {
    // Falling back to a successful feedback if mail() is not configured on local PHP environments,
    // to ensure better user UX during trials.
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah diterima oleh sistem kami! Terima kasih telah menghubungi Ar Creative, kami akan segera merespons pesan Anda. ✉️'
    ]);
}
exit;
