<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';

use Models\UserType;
use Models\LtpApplication;
use Models\STATUS;

allow([UserType::ADMIN, UserType::CLIENT]);

if (isset($_GET['id'])) {
    $item = LtpApplication::get($_GET['id']);
    if (count($item->transport_entries) != 0) {
        $item->submitted_at = date("Y-m-d", strtotime("today"));
        $item->status = Status::SUBMITTED;
        $item->save();
    }
    exit(header('Location: /ltpapplications/update.php?id='.$item->id));
}

