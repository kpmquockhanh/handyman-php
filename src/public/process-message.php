<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact-us');
    exit();
}

// Initialize response array
$response = [
    'success' => false,
    'errors' => []
];

$name = trim(htmlspecialchars($_POST['formName'] ?? ''));
$email = filter_var($_POST['formEmail'] ?? '', FILTER_SANITIZE_EMAIL);
$message = trim(htmlspecialchars($_POST['formMessage'] ?? ''));


// Validate name
if (empty($name) || strlen($name) < 2 || strlen($name) > 100) {
    $response['errors'][] = 'Name must be between 2 and 100 characters';
}

// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['errors'][] = 'Please enter a valid email address';
}

// Validate message
if (empty($message) || strlen($message) < 10) {
    $response['errors'][] = 'Message must be at least 10 characters long';
}

// If there are no validation errors, proceed with saving the message
if (empty($response['errors'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)");
        $result = $stmt->execute([
            'name' => $name,
            'email' => $email,
            'message' => $message
        ]);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Thank you for your message. We will get back to you soon!';
            
            // Optional: Send email notification to admin
            $to = "admin@example.com"; // Replace with your email
            $subject = "New Contact Form Submission";
            $emailBody = "New message from website contact form:\n\n";
            $emailBody .= "Name: $name\n";
            $emailBody .= "Email: $email\n";
            $emailBody .= "Message:\n$message";
            
            mail($to, $subject, $emailBody);
        } else {
            $response['errors'][] = 'Failed to save message. Please try again later.';
        }
    } catch (PDOException $e) {
        $response['errors'][] = 'An error occurred. Please try again later.';
        // Log the error for debugging
        error_log("Contact form error: " . $e->getMessage());
    }
}


header('Content-Type: application/json');
echo json_encode($response);
