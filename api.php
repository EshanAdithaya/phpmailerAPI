<?php

require_once('vendor/autoload.php'); // Assuming Composer autoload
require_once('sender.php'); // Include the EmailSender class

use MyProject\EmailSender;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true); // Get request data

if (isset($data['to'], $data['subject'], $data['body'], $data['websiteName'], $data['requestType'])) {
    $config = require_once('config.php'); // Load configuration

    $sender = new EmailSender($config);

    // **Prepare additional data based on requestType (optional):**
    $additionalData = [];
    if (isset($data['additionalData'])) {
        $additionalData = $data['additionalData'];
    }

    // Pass websiteName to sendEmail method
    $success = $sender->sendEmail(
        $data['to'],
        $data['subject'],
        $data['body'],
        $data['websiteName'], // Use $data['websiteName'] instead of $websiteName
        $data['requestType'],
        $additionalData
    );

    $response = [
        'success' => $success
    ];

    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required data']);
}
