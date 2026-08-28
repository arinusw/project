<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $project = isset($_POST['user_project']) ? trim($_POST['user_project']) : '';
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '';

    if (empty($name) || empty($email) || empty($project)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mohon lengkapi semua kolom pendaftaran / kontak.'
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

    if ($simulation_mode === '1') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil terkirim (Modus Simulasi)! Terima kasih telah menghubungi Ar Creative.'
        ]);
        exit;
    }

    // In a production environment, mail() or an SMTP library like PHPMailer would be called here.
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda kembali.'
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
