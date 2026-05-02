<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $website = strip_tags(trim($_POST["website"]));
    $service = strip_tags(trim($_POST["service"]));

    if (empty($name) || empty($email) || empty($website)) {
        echo json_encode(["status" => "error", "message" => "Please fill all required fields."]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Since I don't have SMTP credentials, I'll use the native mail() function via PHPMailer's isMail()
        // If the user provides SMTP info, they can swap isMail() for isSMTP() and add credentials here.
        $mail->isMail(); 

        $mail->setFrom('no-reply@elaunchsolutions.com', 'eLaunch Solutions');
        $mail->addAddress('rankmonk@gmail.com');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'New SEO Audit Request from ' . $name;
        $mail->Body    = "
            <h3>New Audit Request</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Website:</strong> $website</p>
            <p><strong>Service:</strong> $service</p>
        ";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Thank you! Your request has been sent."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
