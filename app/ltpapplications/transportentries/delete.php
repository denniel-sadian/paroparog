<?php
require_once '/var/www/utilities/twig.php';
require_once '/var/www/utilities/context.php';
require_once '/var/www/utilities/auth/guards.php';
require_once '/var/www/utilities/db/models/users.php';
require_once '/var/www/utilities/db/models/ltpapplication.php';

use Models\UserType;
use Models\LtpApplication;
use Models\Butterfly;
use Models\TransportEntry;

allow([UserType::ADMIN, UserType::CLIENT]);

if (isset($_GET['id'])) {
    $entry = TransportEntry::get($_GET['id']);
    $entry->delete();
    exit(header('Location: /ltpapplications/update.php?id='.$entry->ltpapp_id));
}

