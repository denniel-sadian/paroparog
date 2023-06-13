<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';

use Models\UserType;
use Models\LtpApplication;
use Models\Status;

allow([UserType::ADMIN]);

if (isset($_GET['id'])) {
    $item = LtpApplication::get($_GET['id']);
    $item->accepted_at = date("Y-m-d", strtotime("today"));
    $item->status = Status::ACCEPTED;
    $item->save();

    exit(header('Location: /ltpapplications/update.php?id='.$item->id));
}

