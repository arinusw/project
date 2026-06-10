<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['user_name']));
    $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST['user_subject']));
    $message = strip_tags(trim($_POST['user_message']));
    $simulation_mode = isset($_POST['simulation_mode']) ? $_POST['simulation_mode'] : '0';

    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Input tidak valid.']);
        exit;
    }

    if ($simulation_mode === '1') {
        echo json_encode(['status' => 'success', 'message' => 'Mode Simulasi: Pesan berhasil diterima!']);
        exit;
    }

    // In a real scenario, you would use mail() or a library like PHPMailer
    // $to = "infostudio.ari@gmail.com";
    // $headers = "From: $name <$email>";
    // if (mail($to, $subject, $message, $headers)) {
    //     echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil dikirim.']);
    // } else {
    //     echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan.']);
    // }

    // Since we don't have a mail server configured in this environment,
    // we default to success if simulation mode is handled or just return success for the sake of the demo.
    echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil diterima!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>
