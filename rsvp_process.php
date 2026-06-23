<?php
// rsvp_process.php

$recipient = "inquires@benuebreastcancersupportinitiative.org";
$allowedAttendance = [
    "Yes, I will attend",
    "Yes, with guests",
    "Maybe",
    "Unable to attend, but interested in supporting"
];
$allowedInterest = [
    "General RSVP",
    "Table reservation",
    "Sponsorship",
    "Silent auction donation",
    "Media or protocol enquiry"
];

function clean_text($value) {
    $value = is_string($value) ? $value : "";
    $value = trim($value);
    $value = preg_replace('/[\r\n]+/', ' ', $value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_message($value) {
    $value = is_string($value) ? $value : "";
    $value = trim($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: rsvp.html");
    exit();
}

if (!empty($_POST["website"])) {
    header("Location: rsvp_thank_you.html");
    exit();
}

$name = clean_text($_POST["Name"] ?? "");
$email = filter_var(trim($_POST["Email"] ?? ""), FILTER_SANITIZE_EMAIL);
$phone = clean_text($_POST["Phone"] ?? "");
$organization = clean_text($_POST["Organization"] ?? "");
$attendance = clean_text($_POST["Attendance"] ?? "");
$interest = clean_text($_POST["Interest"] ?? "General RSVP");
$dietary = clean_text($_POST["Dietary"] ?? "");
$message = clean_message($_POST["Message"] ?? "");
$guests = filter_input(INPUT_POST, "Guests", FILTER_VALIDATE_INT, [
    "options" => ["default" => 0, "min_range" => 0, "max_range" => 10]
]);

if (
    empty($name) ||
    empty($email) ||
    empty($phone) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $guests === false ||
    !in_array($attendance, $allowedAttendance, true) ||
    !in_array($interest, $allowedInterest, true)
) {
    http_response_code(422);
    echo "<h2 style='text-align:center;color:#DA2968;font-family:Arial,sans-serif;'>Please complete all required RSVP fields correctly.</h2>";
    echo "<p style='text-align:center;font-family:Arial,sans-serif;'><a href='rsvp.html#rsvp-form'>Return to RSVP form</a></p>";
    exit();
}

$subject = "New RSVP: BBCSI Decade of Impact Gala Night";
$body = "
BBCSI Gala Night RSVP

Name: $name
Email: $email
Phone: $phone
Organization: $organization
Attendance: $attendance
Number of Guests: $guests
Interest: $interest
Dietary or Accessibility Notes: $dietary

Message:
$message

Event:
BBCSI Decade of Impact Gala Night
Saturday, October 17, 2026
6:00 PM
Royal Choice Banquet Hall, Makurdi

----------------------------
This RSVP was submitted from the BBCSI website.
";

$headers = "From: BBCSI Website <no-reply@benuebreastcancersupportinitiative.org>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($recipient, $subject, $body, $headers)) {
    header("Location: rsvp_thank_you.html");
    exit();
}

http_response_code(500);
echo "<h2 style='text-align:center;color:red;font-family:Arial,sans-serif;'>There was an issue sending your RSVP. Please try again later.</h2>";
echo "<p style='text-align:center;font-family:Arial,sans-serif;'><a href='rsvp.html#rsvp-form'>Return to RSVP form</a></p>";
?>
