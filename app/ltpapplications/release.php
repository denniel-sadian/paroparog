<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/messaging/mailer.php';

use Models\UserType;
use Models\LtpApplication;
use Models\Status;

allow([UserType::RELEASING_PERSONNEL]);

if (isset($_GET['id'])) {
    $item = LtpApplication::get($_GET['id']);
    $today = new DateTime();
    $item->validity_date = $today->modify('+'.$_ENV['LTP_VALIDITY_DAYS'].' days')->format('Y-m-d');
    $item->status = Status::RELEASED;
    $item->save();

    notify_client_about_released($item);

    exit(header('Location: /ltpapplications/update.php?id='.$item->id));
}

