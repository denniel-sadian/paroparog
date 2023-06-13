<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/db/models/payment_order.php';

use Models\UserType;
use Models\LtpApplication;
use Models\PaymentOrder;
use Models\Status;

allow([UserType::ADMIN]);

if (isset($_GET['id'])) {
    $or = new PaymentOrder();
    $or->ltpapp_id = $_GET['id'];
    $or->amount = 0;
    $or->payment_signatory_id = null;
    $or->signature_link = null;
    $or->save();

    exit(header('Location: /ltpapplications/update.php?id='.$_GET['id']));
}

