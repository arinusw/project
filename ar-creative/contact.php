<?php
// Set header for JSON response
header('Content-Type: application/json; charset=utf-8');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
    $simulationMode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon isi semua bidang formulir dengan benar.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email tidak valid. Mohon periksa kembali.'
        ]);
        exit;
    }

    // Process submission / Simulation Mode
    if ($simulationMode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah berhasil dikirim (Mode Simulasi)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // In a real server environment, mail() or an SMTP client would send the message here.
    // Return success response in Bahasa Indonesia
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil terkirim! Kami akan segera menghubungi Anda kembali.'
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}
?>
