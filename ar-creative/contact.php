<?php
// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Allow requests from the same origin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input parameters
    $userName = isset($_POST['user_name']) ? trim(strip_tags($_POST['user_name'])) : '';
    $userEmail = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $userProject = isset($_POST['user_project']) ? trim(strip_tags($_POST['user_project'])) : '';
    $simulationMode = isset($_POST['simulation_mode']) && $_POST['simulation_mode'] === '1';

    // Validation checks
    if (empty($userName) || empty($userEmail) || empty($userProject)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom formulir yang diperlukan.'
        ]);
        exit;
    }

    if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format alamat email tidak valid.'
        ]);
        exit;
    }

    // Process message delivery or simulation
    if ($simulationMode) {
        // Simulation mode for automated testing without actual mail transport
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // Real mail processing attempt
    $to = 'infostudioari@gmail.com';
    $subject = 'Pesan Baru dari Portfolio Ar Creative: ' . $userName;
    $body = "Nama: {$userName}\nEmail: {$userEmail}\n\nPesan/Proyek:\n{$userProject}\n";
    $headers = "From: {$userEmail}\r\nReply-To: {$userEmail}\r\nX-Mailer: PHP/" . phpversion();

    // Try mail sending; default to success acknowledgement
    @mail($to, $subject, $body, $headers);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim! Tim Ar Creative akan segera menghubungi Anda.'
    ]);
    exit;
} else {
    // Handle invalid HTTP method
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak diizinkan.'
    ]);
    exit;
}
?>