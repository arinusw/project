<?php
/**
 * Contact Form Handler for Ar Creative
 * Version: 1.0
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitization
    $name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Harap isi semua kolom yang diperlukan.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Alamat email tidak valid.'
        ]);
        exit;
    }

    // Simulation mode / Email sending logic
    $to = "infostudio.ari@gmail.com";
    $subject = "Pesan baru dari Ar Creative: $name";
    $email_content = "Nama: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Pesan:\n$message\n";

    $email_headers = "From: $name <$email>";

    // In a real environment, you would use mail()
    // For this simulation/demo environment, we will always return success
    // @mail($to, $subject, $email_content, $email_headers);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pesan Anda telah berhasil dikirim. Terima kasih!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    ]);
}
?>
