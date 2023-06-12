<?php
use PHPMailer\PHPMailer\PHPMailer;


function send_mail($to_email, $message) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    //$mail->SMTPDebug = 2;
    $mail->Host = $_ENV['EMAIL_HOST'];
    $mail->Port = $_ENV['EMAIL_PORT'];
    $mail->setFrom($_ENV['EMAIL_FROM'], $_ENV['EMAIL_FROM_NAME']);
    if ($_ENV['EMAIL_AUTH'] == 'true') {
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
    }
    $mail->addAddress($to_email, '');
    $mail->Body = $message;
    $mail->send();
}
