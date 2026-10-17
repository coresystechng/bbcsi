<?php
// rsvp_process.php

// Reject submissions from cached forms as well as direct requests.
header("Cache-Control: no-store");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    http_response_code(403);
    header("Content-Type: text/html; charset=UTF-8");
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>RSVP Closed | BBCSI</title><body style="font-family:Arial,sans-serif;background:#fff;color:#555457;padding:3rem 1rem;text-align:center"><main><h1 style="color:#da2968">RSVP Is Now Closed</h1><p>Thank you for your interest and support. No new RSVPs are being accepted.</p><p>For enquiries about an existing RSVP, please contact BBCSI.</p><p><a style="color:#da2968" href="contact.html">Contact BBCSI</a></p><p><a style="color:#da2968" href="rsvp.html#rsvp-form">Back to Gala Page</a></p></main></body></html>';
    exit();
}

header("Location: rsvp.html#rsvp-form");
exit();
