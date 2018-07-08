<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

class Mail
{
    public function __construct($to,$email,$subject,$message)
    {
        $dotenv = new Dotenv(__DIR__ . '/../../');
        $dotenv->load();

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "miroslav.trninic@gmail.com";
        $mail->Password = getenv('MAIL_PASSWORD');
        $mail->setFrom('miroslav.trninic@gmail.com', 'First Last');
        $mail->addReplyTo('miroslav.trninic@gmail.com', 'First Last');
        $mail->addAddress($email, $to);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        //$mail->msgHTML(file_get_contents('.email.html'), __DIR__);
        $mail->Body = $message;
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Message sent!";
        }
        
    }
    
}

