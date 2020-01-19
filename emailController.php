<?php

require_once 'vendor/autoload.php';
require_once './constants.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
  ->setUsername(EMAIL)
  ->setPassword(PASSWORD);
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendVerificationEmail($email, $token){

    //access mailer object inside the function
    global $mailer;

    $body = '<!DOCTYPE html>
    <html>
        <head>
            <title>Verify Email</title>
    
        </head>
    
        <body>
            <div class="wrapper">
                <p>
                    Please click the link below to verify your email
                </p>
    
                <a href="http://localhost/127.0.0.1/index.php?token='.$token.'">
                    Verify Your Email Address
                </a>
            </div>
        </body>
    </html>';

    // Create a message
    $message = (new Swift_Message('Verify Your Email Address'))
    ->setFrom(EMAIL)
    ->setTo($email)
    ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);
    }

?>