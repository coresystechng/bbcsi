<?php
// contact_process.php

// Configure recipient email
$recipient = "inquires@benuebreastcancersupportinitiative.org";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Honeypot trap
    if (!empty($_POST["website"])) {
        // If this field is filled, it's likely a bot
        header("Location: thank_you.html");
        exit();
    }

    // Collect and sanitize inputs
    $name = htmlspecialchars(trim($_POST["Name"]));
    $email = htmlspecialchars(trim($_POST["Email"]));
    $role = htmlspecialchars(trim($_POST["Role"]));
    $message = htmlspecialchars(trim($_POST["Message"]));

    // Validate form fields
    if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Email subject and body
        $subject = "New Contact Form Submission";
        $body = "
        You have received a new message from the BBCSI Contact Form:

        Name: $name
        Email: $email
        Role: $role

        Message:
        $message

        ----------------------------
        This message was sent from the BBCSI website contact form.
        ";

        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send email
        if (mail($recipient, $subject, $body, $headers)) {
            header("Location: thank_you.html");
            exit();
        } else {
            echo "<h2 style='text-align:center;color:red;'>There was an issue sending your message. Please try again later.</h2>";
        }

    } else {
        echo "<h2 style='text-align:center;color:red;'>Please fill in all required fields correctly.</h2>";
    }

} else {
    // Redirect to form if accessed directly
    header("Location: contact.html");
    exit();
}
?>
