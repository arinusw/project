<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles AJAX form submissions.
 */

// Set header for JSON response
header('Content-Type: application/json');

// Get POST data
$name = isset($_POST['user_name']) ? strip_tags(trim($_POST['user_name'])) : '';
$email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST['user_project']) ? strip_tags(trim($_POST['user_project'])) : '';

// Basic validation
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Please fill in all required fields.'
    ]);
    exit;
}

// In a real environment, we would use mail() or a library like PHPMailer.
// For this project, we'll simulate a successful email delivery.
// Simulation mode (always returns success if validation passes)

$recipient = "infostudio.ari@gmail.com";
$subject = "New Contact from $name via Ar Creative Portfolio";
$email_content = "Name: $name\n";
$email_content .= "Email: $email\n\n";
$email_content .= "Message:\n$message\n";
$email_headers = "From: $name <$email>";

// Attempt to send (suppressing errors with @ for environments without mail setup)
// @mail($recipient, $subject, $email_content, $email_headers);

// Always return success for the purpose of this portfolio demo
echo json_encode([
    'status' => 'success',
    'message' => 'Pesan Anda berhasil dikirim.'
]);
?>
