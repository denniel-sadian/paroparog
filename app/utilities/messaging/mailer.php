<?php
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/dtos.php';

use PHPMailer\PHPMailer\PHPMailer;
use Models\User;
use Models\UserType;
use DTOs\Search;


function send_mail($to_email, $message, $subject = "LTP Notification") {
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
        $mail->SMTPSecure = 'tls';
    }
    $mail->addAddress($to_email, '');
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->send();
}

function get_all_users_of_type($type) {
    $users = User::filter_all(Search::create(['type' => UserType::ADMIN]));
    return $users;
}


function notify_admins_about_submitted($ltp) {
    $subj = "Submitted Application: $ltp->no";
    $msg = "LTP application with number $ltp->no has been submitted";
    $users = get_all_users_of_type(UserType::ADMIN);
    foreach ($users as $user) {
        send_mail($user->email, $msg, $subj);
    }
}

function notify_client_about_returned($ltp) {
    $subj = "Returned Application: $ltp->no";
    $msg = "Your application with number $ltp->no is returned by an admin. Please check the remarks.";
    send_mail($ltp->client->email, $msg, $subj);
}

function notify_client_about_accepted($ltp) {
    $subj = "Accepted Application: $ltp->no";
    $msg = "Your application with number $ltp->no is now accepted by an admin.";
    send_mail($ltp->client->email, $msg, $subj);
}

function notify_client_about_released($ltp) {
    $subj = "Released LTP: $ltp->no";
    $msg = "Your Local Transport Permit with number $ltp->no can now be downloaded.";
    send_mail($ltp->client->email, $msg, $subj);
}
