<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/messaging/mailer.php';

use Models\UserType;
use Models\LtpApplication;
use Models\STATUS;

allow([UserType::ADMIN]);

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $item = LtpApplication::get($_GET['id']);
    $item->returned_at = date("Y-m-d", strtotime("today"));
    $item->remarks = $_POST['remarks'];
    $item->status = Status::DRAFT;
    $item->save();
    notify_client_about_returned($item);
}

exit(header('Location: /ltpapplications/list.php'));
