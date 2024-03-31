<?php

namespace MyProject; // Replace with your actual namespace

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {

    private $mailer;

    public function __construct($config) {
        $this->mailer = new PHPMailer(true); // Set exceptions enabled
        $this->configureMailer($config);
    }

    private function configureMailer($config) {
        // SMTP Configuration
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['smtp.gmail.com'];
        $this->mailer->SMTPAuth = $config['true'];
        $this->mailer->Username = $config['crystesoftware@gmail.com'];
        $this->mailer->Password = $config['nqro pynm rurp wmyu'];
        $this->mailer->Port = $config['587'];

        // Email Settings
        $this->mailer->setFrom($config['from_email'], $config['from_name']);
        $this->mailer->addReplyTo($config['reply_to_email'], $config['reply_to_name']);
    }

    public function sendEmail($to, $subject, $body, $websiteName = null, $requestType = null, $additionalData = []) {
        $this->mailer->addAddress($to);
        $this->mailer->Subject = $subject;

        // Customize body based on websiteName, requestType, and additionalData
        $customizedBody = $body;
        if ($websiteName && $requestType) {
            // Implement logic to modify the body based on website and request type
            // You can use string manipulation, templates, or a database lookup
            // to dynamically generate the body content.
            $customizedBody = "This email is from " . $websiteName . " regarding " . $requestType . ". " . $body;
            if (!empty($additionalData)) {
                // Include additional data if provided
                $customizedBody .= "\nAdditional Data:\n";
                foreach ($additionalData as $key => $value) {
                    $customizedBody .= "- " . $key . ": " . $value . "\n";
                }
            }
        }

        $this->mailer->Body = $customizedBody;
        $this->mailer->isHTML(true); // Assuming HTML content

        try {
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        } finally {
            $this->mailer->clearAllRecipients(); // Clear recipients for next email
        }
    }
}
