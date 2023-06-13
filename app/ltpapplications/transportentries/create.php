<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplications.php';

use Models\UserType;
use Models\LtpApplication;
use Models\Butterfly;
use Models\TransportEntry;

allow([UserType::ADMIN, UserType::CLIENT]);

if (isset($_POST['submit'])) {
    $entry = new TransportEntry();
    $entry->ltpapp_id = $_POST['ltpapp_id'];
    $entry->animal_id = $_POST['animal_id'];
    $entry->quantity = $_POST['quantity'];
    $entry->description = $_POST['description'];
    $entry->save();
}

exit(header('Location: /ltpapplications/update.php?id='.$_POST['ltpapp_id']));
