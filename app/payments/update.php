<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';
require_once '/var/www/utilities/db/models/payment_order.php';
require_once '/var/www/utilities/files/uploader.php';

use Models\UserType;
use Models\LtpApplication;
use Models\PaymentOrder;
use Models\Status;

allow([UserType::PAYMENT_SIGNATORY]);

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $or = PaymentOrder::get($_GET['id']);
    $or->payment_signatory_id = $CONTEXT['user']->id;
    $or->amount = $_POST['amount'];

    if (isset($_FILES['signature'])) {
        $signature_link = save($_FILES['signature']);
        if ($signature_link != null) $or->signature_link = $signature_link;
    }

    $or->save();

    exit(header('Location: /ltpapplications/update.php?id='.$or->ltpapp_id));
}

