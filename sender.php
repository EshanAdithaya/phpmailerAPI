<?php

namespace MyProject; // Replace with your namespace

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {

    private $mailer;

    public function __construct($config) {
        $this->mailer = new PHPMailer(true); // Set exceptions enabled
        $this->configureMailer($config);
    }

    private function configureMailer($config) {
        // SMTP Configuration (unchanged)
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['smtp.gmail.com'];
        $this->mailer->SMTPAuth = $config['true'];
        $this->mailer->Username = $config['crystesoftware@gmail.com'];
        $this->mailer->Password = $config['nqro pynm rurp wmyu'];
        $this->mailer->Port = $config['587']; // Adjust if needed

        // Email Settings (unchanged)
        $this->mailer->setFrom($config['crystesoftware@gmail.com'], $config['Eshan Adithaya Gunathilaka']);
        // edit addReplyTo($config['crystesoftware@gmail.com'] and $config['test Eshan Adithaya Gunathilaka']);
        $this->mailer->addReplyTo($config['crystesoftware@gmail.com'], $config['test Eshan Adithaya Gunathilaka']);
    }

    public function sendEmail($to, $subject, $body, $websiteName = null, $requestType = null, $additionalData = []) {
        $this->mailer->addAddress($to);
        $this->mailer->Subject = $subject;

        // **Customize body based on websiteName, requestType, and additionalData (optional):**
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
