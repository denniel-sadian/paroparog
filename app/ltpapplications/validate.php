<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/db/models/dtos.php';

use Models\UserType;
use Models\LtpApplication;
use Models\Status;
use DTOs\Search;

allow([UserType::VALIDATOR]);

if (isset($_GET['no'])) {
    $item = LtpApplication::filter_all(Search::create(['no' => $_GET['no']]))[0];

    $currentDate = new DateTime();
    $currentDate->setTime(0, 0, 0);
    $validityDate = DateTime::createFromFormat('Y-m-d', $item->validity_date);
    $validityDate->setTime(0, 0, 0);
    $isStillValid = $validityDate >= $currentDate;

    if ($item->status == Status::RELEASED && $isStillValid) {
        $item->status = Status::USED;
        $item->save();
        $CONTEXT['status'] = 'used';
    } elseif ($item->status == Status::USED) {
        $CONTEXT['status'] = 'already_used';
    } elseif ($item->status == Status::EXPIRED || ($item->status == Status::RELEASED && !$isStillValid)) {
        $item->status = Status::EXPIRED;
        $item->save();
        $CONTEXT['status'] = 'expired';
    }

    $CONTEXT['ltp'] = $item;

    echo $twig->render('validate.html', $CONTEXT);
}

