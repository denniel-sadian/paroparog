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

allow([UserType::CLIENT]);

if (isset($_POST['submit']) && isset($_GET['id'])) {
    $or = PaymentOrder::get($_GET['id']);
    $or->or_no = $_POST['or_no'];

    if (isset($_FILES['or'])) {
        $or_link = save($_FILES['or']);
        if ($or_link != null) $or->or_link = $or_link;
    }

    $or->save();

    exit(header('Location: /ltpapplications/update.php?id='.$or->ltpapp_id));
}
