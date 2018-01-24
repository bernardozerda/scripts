<?php

require "./php_mailer/src/PHPMailer.php";
require "./php_mailer/src/Exception.php";
require "./php_mailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
try {

    //Server settings
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bernardo.zerda@habitatbogota.gov.co';
    $mail->Password = 'Lafechadenavidad*1';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //Recipients
    $mail->setFrom($mail->Username, 'Bernardo Zerda');
    $mail->addAddress($mail->Username);     // Add a recipient
    $mail->addReplyTo($mail->Username, 'Bernardo Zerda');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Prueba';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

    $mail->send();
    echo 'Message has been sent';

} catch (Exception $e) {

    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;

}


?>