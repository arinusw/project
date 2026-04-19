<?php
/**
 * Ar Creative - Contact Form Handler
 * Handles form submissions from the portfolio website.
 */

// Set response header to plain text
header('Content-Type: text/plain');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = isset($_POST['user_name']) ? htmlspecialchars(trim($_POST['user_name'])) : '';
    $email = isset($_POST['user_email']) ? filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL) : '';
    $message = isset($_POST['user_project']) ? htmlspecialchars(trim($_POST['user_project'])) : '';

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format.";
        exit;
    }

    // Simulation Mode: In development environments, we don't actually send mail
    // This allows UI verification without a configured mail server.
    $simulation_mode = true;

    if ($simulation_mode) {
        // Simulate a successful mail delivery
        http_response_code(200);
        echo "Simulation: Message from $name ($email) received successfully.";
    } else {
        // Production Mode: Attempt to send actual email
        $to = "infostudio.ari@gmail.com";
        $subject = "New Contact Form Message from Ar Creative Portfolio";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        // Use @ to suppress errors if mail server is not configured
        if (@mail($to, $subject, $body, $headers)) {
            http_response_code(200);
            echo "Success: Message sent.";
        } else {
            http_response_code(500);
            echo "Error: Could not send message.";
        }
    }
} else {
    // Not a POST request
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
